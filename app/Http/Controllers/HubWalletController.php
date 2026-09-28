<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\WalletTransaction;

class HubWalletController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        if (!$franchise && !$isHubManager) {
            abort(403, 'Unauthorized');
        }

        return [
            'user' => $user,
            'franchise_id' => $franchise ? $franchise->id : null,
        ];
    }

    public function index()
    {
        $scope = $this->getAuthScope();
        $user = $scope['user'];
        
        $transactions = [];
        if (class_exists(WalletTransaction::class)) {
            $transactions = WalletTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('hub.wallet.index', compact('user', 'transactions'));
    }
}
