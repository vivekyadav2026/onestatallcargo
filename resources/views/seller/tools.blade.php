@extends('layouts.seller')
@section('title', 'Tools & Calculator - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-[1200px]" x-data="toolsManager()">
    
    <!-- Header -->
    <div>
        <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">Tools</h1>
        <p class="text-sm text-gray-500 mt-1">Calculate shipping rates, compare courier rate charts, check pincode serviceability and track your bulk activity logs — all in one place.</p>
    </div>

    <!-- Tabs Container -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Tab Headers -->
        <div class="flex border-b border-gray-100 px-2 pt-2 overflow-x-auto whitespace-nowrap">
            <button @click="activeTab = 'calculator'" :class="activeTab === 'calculator' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Rate Calculator</button>
            <button @click="activeTab = 'chart'" :class="activeTab === 'chart' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Rate Chart</button>
            <button @click="activeTab = 'serviceability'" :class="activeTab === 'serviceability' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Serviceable Pincodes</button>
            <button @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Activity Logs</button>
        </div>
        
        <!-- TAB 1: Rate Calculator -->
        <div x-show="activeTab === 'calculator'" class="flex flex-col md:flex-row bg-[#fbfcfd] min-h-[500px]" style="display: none;">
            <!-- Left Sidebar Form -->
            <div class="w-full md:w-[350px] bg-white border-r border-gray-200 p-6 shrink-0">
                <form @submit.prevent="calculateRates" class="space-y-6">
                    <!-- Pincodes -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-location-dot text-[#4338ca] text-sm"></i>
                            <h3 class="font-bold text-gray-800 text-sm">Enter Pickup and Delivery Pincodes</h3>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-xs text-gray-500 font-medium mb-1.5">From</label>
                                <input type="text" x-model="calc.pickup" @input="fetchCity(calc.pickup, 'pickupCity')" maxlength="6" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition" :class="calc.pickupCity ? 'border-green-400' : 'border-gray-300'">
                                <p x-show="calc.pickupCity" class="text-[10px] text-green-600 mt-1 flex items-center gap-1 leading-tight"><i class="fa-solid fa-check"></i> <span x-text="calc.pickupCity"></span></p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs text-gray-500 font-medium mb-1.5">To</label>
                                <input type="text" x-model="calc.delivery" @input="fetchCity(calc.delivery, 'deliveryCity')" maxlength="6" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition" :class="calc.deliveryCity ? 'border-green-400' : 'border-gray-300'">
                                <p x-show="calc.deliveryCity" class="text-[10px] text-green-600 mt-1 flex items-center gap-1 leading-tight"><i class="fa-solid fa-check"></i> <span x-text="calc.deliveryCity"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="calc.isDocument" class="w-4 h-4 text-[#4338ca] border-gray-300 rounded focus:ring-[#4338ca]">
                            <span class="text-xs text-gray-700 font-medium">Is this shipment a document?</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="calc.isReverse" class="w-4 h-4 text-[#4338ca] border-gray-300 rounded focus:ring-[#4338ca]">
                            <span class="text-xs text-gray-700 font-medium">Is this a reverse pickup?</span>
                        </label>
                    </div>

                    <!-- Package Details -->
                    <div x-show="!calc.isDocument" x-transition>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-circle-check text-[#4338ca] text-sm"></i>
                            <h3 class="font-bold text-gray-800 text-sm">Package Details</h3>
                        </div>
                        <div class="flex gap-3 items-center">
                            <div class="relative w-24">
                                <input type="number" step="0.01" x-model="calc.weight" class="w-full border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:border-[#4338ca]">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-400">kg</span>
                            </div>
                            
                            <div class="flex bg-gray-50 rounded-lg p-1 border border-gray-200 flex-1">
                                <button type="button" @click="calc.payment = 'prepaid'" :class="calc.payment === 'prepaid' ? 'bg-[#0f172a] text-white shadow' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-1.5 text-xs font-bold rounded-md transition">PREPAID</button>
                                <button type="button" @click="calc.payment = 'cod'" :class="calc.payment === 'cod' ? 'bg-[#0f172a] text-white shadow' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-1.5 text-xs font-bold rounded-md transition">COD</button>
                            </div>
                        </div>
                        <div x-show="calc.payment === 'cod'" class="mt-2" x-transition>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Shipment Value (&#8377;)</label>
                            <input type="number" x-model="calc.shipmentValue" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#4338ca]">
                        </div>
                    </div>

                    <!-- Dimensions -->
                    <div x-show="!calc.isDocument" x-transition>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-pencil text-[#4338ca] text-sm"></i>
                            <h3 class="font-bold text-gray-800 text-sm">Dimensions (cm)</h3>
                        </div>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">L</span>
                                <input type="number" x-model="calc.length" class="w-full border border-gray-300 rounded-lg pl-5 pr-1 py-2 text-sm focus:outline-none focus:border-[#4338ca]">
                            </div>
                            <div class="relative flex-1">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">W</span>
                                <input type="number" x-model="calc.width" class="w-full border border-gray-300 rounded-lg pl-5 pr-1 py-2 text-sm focus:outline-none focus:border-[#4338ca]">
                            </div>
                            <div class="relative flex-1">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">H</span>
                                <input type="number" x-model="calc.height" class="w-full border border-gray-300 rounded-lg pl-5 pr-1 py-2 text-sm focus:outline-none focus:border-[#4338ca]">
                            </div>
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-[10px] font-bold text-gray-400">Volumetric Weight: <span class="text-blue-600" x-text="volumetricWeight.toFixed(2) + ' kg'"></span></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#4338ca] hover:bg-[#3730a3] text-white font-bold rounded-lg text-sm transition shadow-sm">
                        <span x-show="!calc.loading">Calculate Rates</span>
                        <span x-show="calc.loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</span>
                    </button>
                </form>
            </div>

            <!-- Right Results Area -->
            <div class="flex-1 p-8 flex flex-col items-center justify-center text-center">
                <!-- Empty State -->
                <div x-show="!calc.hasResults && !calc.loading" class="max-w-xs">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center text-gray-300 mb-6">
                        <i class="fa-solid fa-calculator text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Enter shipment details to calculate</h3>
                    <p class="text-xs text-gray-500 font-medium">Results will show available couriers, their itemized charges, and volumetric calculations.</p>
                </div>

                <!-- Error State -->
                <div x-show="calc.error && !calc.loading" class="bg-red-50 text-red-600 p-4 rounded-lg font-medium text-sm w-full max-w-md">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> <span x-text="calc.error"></span>
                </div>

                <!-- Loading State -->
                <div x-show="calc.loading" class="text-gray-400 flex flex-col items-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-4 text-[#4338ca]"></i>
                    <p class="text-sm font-semibold text-gray-600">Fetching live rates...</p>
                </div>

                <!-- Results Table -->
                <div x-show="calc.hasResults && !calc.loading" class="w-full text-left" style="display: none;">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900 text-lg">Available Couriers</h3>
                        <div class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            Chargeable Weight: <span class="text-[#4338ca]" x-text="chargeableWeight.toFixed(2) + ' kg'"></span>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                                <tr>
                                    <th class="px-6 py-4">Courier Partner</th>
                                    <th class="px-6 py-4">Est. Delivery</th>
                                    <th class="px-6 py-4" x-show="calc.payment === 'cod'">COD Charge</th>
                                    <th class="px-6 py-4 text-right">Total Charge</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="rate in calc.rates" :key="rate.courier_name">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900" x-text="rate.courier_name"></div>
                                            <div class="text-[10px] font-semibold text-gray-500 mt-1"><span class="px-1.5 py-0.5 bg-gray-200 rounded text-gray-600">Surface</span></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-700"><span x-text="rate.estimated_delivery_days"></span> Days</div>
                                        </td>
                                        <td class="px-6 py-4" x-show="calc.payment === 'cod'">
                                            <div class="font-bold text-yellow-600">&#8377; <span x-text="calculateCodCharge().toFixed(2)"></span></div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-lg font-black text-[#4338ca]">&#8377; <span x-text="(rate.rate + calculateCodCharge()).toFixed(2)"></span></div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: Rate Chart -->
        <div x-show="activeTab === 'chart'" class="p-8 bg-[#fbfcfd] min-h-[500px]" style="display: none;">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Standard Rate Chart</h2>
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                            <tr>
                                <th class="px-6 py-4">Courier</th>
                                <th class="px-6 py-4">Weight Slab</th>
                                <th class="px-6 py-4">Forward Rate (Local)</th>
                                <th class="px-6 py-4">Forward Rate (National)</th>
                                <th class="px-6 py-4">COD %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">Delhivery Surface</td>
                                <td class="px-6 py-4 text-gray-500">500 gm</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">&#8377; 45.00</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">&#8377; 65.00</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">2%</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">XpressBees Surface</td>
                                <td class="px-6 py-4 text-gray-500">500 gm</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">&#8377; 42.00</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">&#8377; 62.00</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">1.8%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: Serviceable Pincodes (Matches Screenshot 1) -->
        <div x-show="activeTab === 'serviceability'" class="p-6 bg-[#f8fafc] min-h-[500px]" style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 max-w-6xl mx-auto">
                
                <!-- Left Box: Download Serviceable Pincodes List -->
                <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-[#4338ca]"></i> Download Serviceable Pincodes List
                        </h3>

                        <form @submit.prevent="checkPincode" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Pickup Pincode <span class="text-gray-400 font-normal">(For Zone Mapping)</span></label>
                                <input type="text" x-model="pincodeCheck" maxlength="6" placeholder="e.g. 110001" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4338ca] font-medium">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-[#818cf8] hover:bg-[#6366f1] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i> Download
                            </button>
                        </form>
                    </div>

                    <p class="text-[11px] text-gray-400 mt-6 leading-relaxed">
                        Exports run in the background. You can queue more pincodes while others are processing — track them under Download History.
                    </p>
                </div>

                <!-- Right Box: Download History -->
                <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                    <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> Download History
                    </h3>

                    <!-- Search Filter Row -->
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <input type="text" placeholder="Pincode" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 outline-none w-32">
                        
                        <select class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 outline-none">
                            <option>All Status</option>
                            <option>Completed</option>
                            <option>Processing</option>
                        </select>

                        <div class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 flex items-center gap-2">
                            <span>26/08/2026 ~ 25/09/2026</span>
                            <i class="fa-solid fa-xmark text-gray-400 cursor-pointer"></i>
                        </div>
                    </div>

                    <!-- History Items Container -->
                    <div class="space-y-3">
                        <div class="p-4 bg-gray-50/60 rounded-2xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <div class="font-mono text-xs font-bold text-gray-800">6ab633038c86ccc5f3fa0cac</div>
                                <div class="text-xs text-gray-500 font-semibold mt-0.5">Pincode: <span class="text-gray-900 font-bold">110059</span></div>
                                <div class="text-[10px] text-gray-400 mt-1">25 Sept 2026, 14:08</div>
                                <div class="text-[10px] text-gray-400">29,576 rows &middot; 57 couriers</div>
                            </div>

                            <div class="flex flex-col items-end gap-3">
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-extrabold border border-green-100">Completed</span>
                                <button class="px-3 py-1 bg-white border border-gray-200 text-gray-700 font-bold text-xs rounded-lg hover:bg-gray-50 shadow-sm flex items-center gap-1.5">
                                    <i class="fa-solid fa-download text-gray-400"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 4: Activity Logs -->
        <div x-show="activeTab === 'logs'" class="p-8 bg-[#fbfcfd] min-h-[500px]" style="display: none;">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-xl font-bold text-gray-900 mb-4">System Activity Logs</h2>
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                            <tr>
                                <th class="px-6 py-4">Date / Time</th>
                                <th class="px-6 py-4">Action</th>
                                <th class="px-6 py-4">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500 font-medium">Just now</td>
                                <td class="px-6 py-4 font-bold text-gray-900"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-[10px] uppercase mr-2 border border-blue-100">System</span></td>
                                <td class="px-6 py-4 text-gray-600">Logged into Seller Dashboard</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toolsManager() {
    return {
        activeTab: 'calculator',
        
        calc: {
            pickup: '',
            delivery: '',
            pickupCity: '',
            deliveryCity: '',
            isDocument: false,
            isReverse: false,
            weight: 0.5,
            length: 10,
            width: 10,
            height: 10,
            payment: 'prepaid',
            shipmentValue: 500,
            loading: false,
            hasResults: false,
            error: null,
            rates: [],
        },

        pincodeCheck: '',
        pincodeLoading: false,
        pincodeResult: false,

        get volumetricWeight() {
            return (this.calc.length * this.calc.width * this.calc.height) / 5000;
        },
        
        get chargeableWeight() {
            if (this.calc.isDocument) return 0.5;
            return Math.max(parseFloat(this.calc.weight) || 0.1, this.volumetricWeight);
        },
        
        calculateCodCharge() {
            if (this.calc.payment !== 'cod') return 0;
            let charge = (parseFloat(this.calc.shipmentValue) || 0) * 0.02;
            return Math.max(charge, 50);
        },

        async fetchCity(pincode, targetVar) {
            if(pincode.length !== 6) {
                this.calc[targetVar] = '';
                return;
            }
            try {
                let res = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                let data = await res.json();
                if(data && data[0].Status === 'Success') {
                    let po = data[0].PostOffice[0];
                    this.calc[targetVar] = `${po.District}, ${po.State}`;
                } else {
                    this.calc[targetVar] = 'Invalid Pincode';
                }
            } catch(e) {
                this.calc[targetVar] = '';
            }
        },
        
        async calculateRates() {
            if(this.calc.pickup.length !== 6 || this.calc.delivery.length !== 6) {
                this.calc.error = "Please enter valid 6-digit pincodes.";
                return;
            }
            this.calc.loading = true;
            this.calc.error = null;
            this.calc.hasResults = false;
            
            try {
                let response = await fetch('/api/v1/public/rates', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        pickup_pincode: this.calc.pickup,
                        delivery_pincode: this.calc.delivery,
                        weight: this.chargeableWeight
                    })
                });
                
                let result = await response.json();
                if(response.ok && result.success && result.data.length > 0) {
                    this.calc.rates = result.data;
                    this.calc.hasResults = true;
                } else {
                    this.calc.error = result.message || 'No courier service available.';
                }
            } catch(e) {
                this.calc.error = 'Failed to connect to rate server. Try again.';
            }
            this.calc.loading = false;
        },

        async checkPincode() {
            if(this.pincodeCheck.length !== 6) return;
            this.pincodeLoading = true;
            this.pincodeResult = false;
            
            setTimeout(() => {
                this.pincodeResult = true;
                this.pincodeLoading = false;
            }, 600);
        }
    }
}
</script>
@endsection
