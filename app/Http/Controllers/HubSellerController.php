<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\ServiceablePincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubSellerController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        $isAuthorized = $user->isAdmin()
            || in_array($user->role, ['super_admin', 'admin', 'franchise'])
            || $franchise
            || $isHubManager;

        if (!$isAuthorized) {
            abort(403, 'Unauthorized');
        }

        return [
            'user' => $user,
            'franchise' => $franchise,
            'hub' => Hub::where('manager_id', $user->id)->first()
        ];
    }

    public function index(Request $request)
    {
        $scope = $this->getAuthScope();
        $user = $scope['user'];
        $franchise = $scope['franchise'];
        $hub = $scope['hub'];

        $query = User::whereIn('role', ['seller', 'b2b_customer', 'corporate']);

        if ($franchise) {
            if ($franchise->isState()) {
                // =========================================================================
                // 1. STATE FRANCHISE SCOPE
                // Must strictly see ONLY sellers operating in or registered in their state
                // =========================================================================
                $state = trim($franchise->state ?? '');
                $districtIds = $franchise->districtFranchises()->pluck('id')->toArray();

                // Aggregate all pincodes in this State (from ServiceablePincode and child District Franchises)
                $statePincodes = [];
                if ($state) {
                    $statePincodes = ServiceablePincode::where(function ($spq) use ($state, $districtIds) {
                        $spq->where('state', 'LIKE', "%{$state}%");
                        if (!empty($districtIds)) {
                            $spq->orWhereIn('franchise_id', $districtIds);
                        }
                    })->pluck('pincode')->filter()->toArray();
                }

                $childDistricts = $franchise->districtFranchises;
                foreach ($childDistricts as $df) {
                    $dfPins = is_array($df->serviceable_pincodes) ? $df->serviceable_pincodes : (json_decode($df->serviceable_pincodes, true) ?? []);
                    if (is_array($dfPins)) {
                        $statePincodes = array_merge($statePincodes, $dfPins);
                    }
                }
                $statePincodes = array_values(array_unique(array_filter($statePincodes)));

                $query->where(function ($q) use ($state, $statePincodes, $districtIds, $franchise) {
                    $hasCondition = false;

                    // Condition 1: Seller registered company_state matches Franchise State
                    if ($state) {
                        $lowerState = strtolower($state);
                        $q->where(function ($sq) use ($state, $lowerState) {
                            $sq->whereNotNull('company_state')
                               ->where('company_state', '!=', '')
                               ->where(function ($stq) use ($state, $lowerState) {
                                   $stq->whereRaw('LOWER(TRIM(company_state)) = ?', [$lowerState])
                                       ->orWhere('company_state', 'LIKE', "%{$state}%");
                               });
                        });
                        $hasCondition = true;
                    }

                    // Condition 2: Seller registered company_pincode is in State's serviceable pincodes
                    if (!empty($statePincodes)) {
                        if ($hasCondition) {
                            $q->orWhereIn('company_pincode', $statePincodes);
                        } else {
                            $q->whereIn('company_pincode', $statePincodes);
                            $hasCondition = true;
                        }
                    }

                    // Condition 3: Seller has a pickup warehouse located in this State
                    $warehouseCondition = function ($wq) use ($state, $statePincodes) {
                        $wq->where(function ($w) use ($state, $statePincodes) {
                            if ($state) {
                                $lowerState = strtolower($state);
                                $w->whereRaw('LOWER(TRIM(state)) = ?', [$lowerState])
                                  ->orWhere('state', 'LIKE', "%{$state}%");
                            }
                            if (!empty($statePincodes)) {
                                $w->orWhereIn('pincode', $statePincodes);
                            }
                        });
                    };

                    if ($state || !empty($statePincodes)) {
                        if ($hasCondition) {
                            $q->orWhereHas('warehouses', $warehouseCondition);
                        } else {
                            $q->whereHas('warehouses', $warehouseCondition);
                            $hasCondition = true;
                        }
                    }

                    // Condition 4: Seller has shipments originating in, routed through, or assigned to this State Franchise
                    $shipmentCondition = function ($sq) use ($franchise, $districtIds, $state, $statePincodes) {
                        $sq->where(function ($s) use ($franchise, $districtIds, $state, $statePincodes) {
                            $s->where('state_franchise_id', $franchise->id)
                              ->orWhere('pickup_franchise_id', $franchise->id);

                            if (!empty($districtIds)) {
                                $s->orWhereIn('franchise_id', $districtIds)
                                  ->orWhereIn('pickup_franchise_id', $districtIds);
                            }

                            if (!empty($statePincodes)) {
                                $s->orWhereIn('pickup_pincode', $statePincodes);
                            }
                        });
                    };

                    if ($hasCondition) {
                        $q->orWhereHas('shipments', $shipmentCondition);
                    } else {
                        $q->whereHas('shipments', $shipmentCondition);
                        $hasCondition = true;
                    }

                    // Strict protection: If state franchise has no state set at all, show none
                    if (!$hasCondition) {
                        $q->whereRaw('1 = 0');
                    }
                });

            } else {
                // =========================================================================
                // 2. DISTRICT FRANCHISE SCOPE
                // Scoped to District's assigned pincodes / city / shipments
                // =========================================================================
                $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
                if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }

                $dbPins = ServiceablePincode::where('franchise_id', $franchise->id)->pluck('pincode')->filter()->toArray();
                $pincodes = array_values(array_unique(array_filter(array_merge($pincodes, $dbPins))));
                $city = trim($franchise->city ?? '');

                $query->where(function ($q) use ($pincodes, $city, $franchise) {
                    $matched = false;

                    if (!empty($pincodes)) {
                        $q->whereIn('company_pincode', $pincodes)
                          ->orWhereHas('warehouses', function ($wq) use ($pincodes) {
                              $wq->whereIn('pincode', $pincodes);
                          })
                          ->orWhereHas('shipments', function ($sq) use ($pincodes, $franchise) {
                              $sq->whereIn('pickup_pincode', $pincodes)
                                 ->orWhere('franchise_id', $franchise->id)
                                 ->orWhere('pickup_franchise_id', $franchise->id);
                          });
                        $matched = true;
                    }

                    if ($city) {
                        $lowerCity = strtolower($city);
                        $cityCondition = function ($cq) use ($city, $lowerCity, $franchise) {
                            $cq->whereRaw('LOWER(TRIM(company_city)) = ?', [$lowerCity])
                               ->orWhere('company_city', 'LIKE', "%{$city}%")
                               ->orWhereHas('warehouses', function ($wq) use ($city, $lowerCity) {
                                   $wq->whereRaw('LOWER(TRIM(city)) = ?', [$lowerCity])
                                      ->orWhere('city', 'LIKE', "%{$city}%");
                               })
                               ->orWhereHas('shipments', function ($sq) use ($city, $lowerCity, $franchise) {
                                   $sq->whereRaw('LOWER(TRIM(pickup_city)) = ?', [$lowerCity])
                                      ->orWhere('pickup_city', 'LIKE', "%{$city}%")
                                      ->orWhere('franchise_id', $franchise->id)
                                      ->orWhere('pickup_franchise_id', $franchise->id);
                               });
                        };

                        if ($matched) {
                            $q->orWhere($cityCondition);
                        } else {
                            $q->where($cityCondition);
                            $matched = true;
                        }
                    }

                    if (!$matched) {
                        // If no pincodes or city assigned, show only sellers who explicitly booked with this franchise
                        $q->whereHas('shipments', function ($sq) use ($franchise) {
                            $sq->where('franchise_id', $franchise->id)
                               ->orWhere('pickup_franchise_id', $franchise->id);
                        });
                    }
                });
            }
        } elseif ($hub) {
            // =========================================================================
            // 3. INTERNAL HUB MANAGER SCOPE
            // Scoped to Hub's city or hub pincode
            // =========================================================================
            $city = trim($hub->city ?? '');
            $pincode = $hub->pincode && $hub->pincode !== '000000' ? trim($hub->pincode) : null;

            $query->where(function ($q) use ($city, $pincode) {
                $hasCond = false;
                if ($pincode) {
                    $q->where('company_pincode', $pincode)
                      ->orWhereHas('warehouses', function ($wq) use ($pincode) {
                          $wq->where('pincode', $pincode);
                      })
                      ->orWhereHas('shipments', function ($sq) use ($pincode) {
                          $sq->where('pickup_pincode', $pincode);
                      });
                    $hasCond = true;
                }

                if ($city) {
                    $lowerCity = strtolower($city);
                    $cityCondition = function ($cq) use ($city, $lowerCity) {
                        $cq->whereRaw('LOWER(TRIM(company_city)) = ?', [$lowerCity])
                           ->orWhere('company_city', 'LIKE', "%{$city}%")
                           ->orWhereHas('warehouses', function ($wq) use ($city, $lowerCity) {
                               $wq->whereRaw('LOWER(TRIM(city)) = ?', [$lowerCity])
                                  ->orWhere('city', 'LIKE', "%{$city}%");
                           })
                           ->orWhereHas('shipments', function ($sq) use ($city, $lowerCity) {
                               $sq->whereRaw('LOWER(TRIM(pickup_city)) = ?', [$lowerCity])
                                  ->orWhere('pickup_city', 'LIKE', "%{$city}%");
                           });
                    };

                    if ($hasCond) {
                        $q->orWhere($cityCondition);
                    } else {
                        $q->where($cityCondition);
                        $hasCond = true;
                    }
                }

                if (!$hasCond) {
                    $q->whereRaw('1 = 0');
                }
            });
        } elseif (!$user->isAdmin()) {
            // Non-admin without franchise/hub record has no seller access
            $query->whereRaw('1 = 0');
        }

        // Apply Search (always restricted within the authorized scope)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('company_pincode', 'LIKE', "%{$search}%")
                  ->orWhere('company_city', 'LIKE', "%{$search}%")
                  ->orWhere('company_state', 'LIKE', "%{$search}%");
            });
        }

        // Get counts
        $sellers = $query->withCount([
            'shipments as total_shipments',
            'shipments as pending_pickups' => function ($q) {
                $q->whereIn('status', ['Pending', 'Pickup Scheduled']);
            }
        ])->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('hub.sellers.index', compact('sellers', 'franchise'));
    }
}
