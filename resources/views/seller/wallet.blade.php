@extends('layouts.seller')
@section('title', 'Billing & Wallet - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-[1400px]" x-data="walletManager()">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">Billing & Wallet</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your prepaid wallet, view transaction history, and download tax invoices.</p>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Column: Balance & Recharge -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Beautiful Balance Card -->
            <div class="bg-gradient-to-br from-[#0f172a] to-[#1e1b4b] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <!-- Abstract BG shapes -->
                <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-10 -mt-10"></div>
                <div class="absolute left-0 bottom-0 w-24 h-24 bg-[#E8027D] opacity-40 rounded-full blur-xl -ml-10 -mb-10"></div>
                
                <div class="relative z-10">
                    <p class="text-sm text-gray-300 font-semibold mb-1 flex items-center"><i class="fa-solid fa-wallet mr-2"></i> Available Balance</p>
                    <h2 class="text-4xl font-extrabold tracking-tight mb-6">&#8377; {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</h2>
                    
                    <p class="text-xs text-gray-400">Low balance may pause your order processing. Please keep your wallet funded.</p>
                </div>
            </div>

            <!-- Recharge Box -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-gray-900 text-lg mb-4">Recharge Wallet</h3>
                
                <form @submit.prevent="initiateRecharge">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Enter Amount</label>
                    <div class="relative mb-4">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-lg">&#8377;</span>
                        <input type="number" x-model="amount" min="100" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#E8027D] focus:ring-1 focus:ring-[#E8027D] font-bold text-gray-900 text-lg transition" required>
                    </div>

                    <!-- Quick Chips -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <button type="button" @click="amount = 1000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">+ &#8377;1,000</button>
                        <button type="button" @click="amount = 5000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">+ &#8377;5,000</button>
                        <button type="button" @click="amount = 10000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">+ &#8377;10,000</button>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#E8027D] hover:bg-[#d60070] text-white font-bold rounded-lg text-sm transition shadow-sm flex justify-center items-center gap-2" :disabled="loading">
                        <span x-show="!loading">Proceed to Pay</span>
                        <span x-show="loading"><i class="fa-solid fa-circle-notch fa-spin"></i> Processing...</span>
                    </button>
                    
                    <div class="mt-4 flex items-center justify-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo-vector.svg" class="h-4 opacity-70 grayscale" alt="UPI">
                        <i class="fa-brands fa-cc-visa text-gray-400 text-xl"></i>
                        <i class="fa-brands fa-cc-mastercard text-gray-400 text-xl"></i>
                        <span class="text-[10px] text-gray-400 font-semibold ml-1">Secured by Cashfree</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Transactions & Invoices -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                
                <!-- Internal Tabs -->
                <div class="flex border-b border-gray-100 px-4 pt-2 bg-gray-50">
                    <button @click="view = 'transactions'" :class="view === 'transactions' ? 'text-[#E8027D] border-b-2 border-[#E8027D] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-4 py-3 text-sm transition-colors">Transaction Ledger</button>
                    <button @click="view = 'invoices'" :class="view === 'invoices' ? 'text-[#E8027D] border-b-2 border-[#E8027D] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-4 py-3 text-sm transition-colors">Tax Invoices</button>
                </div>

                <!-- Transactions Ledger -->
                <div x-show="view === 'transactions'">
                    @php
                        $transactions = \App\Models\WalletTransaction::where('user_id', Auth::id())->latest()->limit(50)->get();
                    @endphp
                    <div class="overflow-y-auto max-h-[500px]">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-white sticky top-0 border-b border-gray-200 text-[11px] text-gray-500 font-bold uppercase tracking-wider z-10">
                                <tr>
                                    <th class="px-6 py-4">Txn ID / Date</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4 text-right">Amount</th>
                                    <th class="px-6 py-4 text-right">Closing Bal.</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($transactions as $txn)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $txn->transaction_id ?? 'TXN-'.$txn->id }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700 flex items-center gap-2">
                                            @if($txn->type === 'credit')
                                                <i class="fa-solid fa-arrow-down text-green-500"></i> Wallet Recharge
                                            @else
                                                <i class="fa-solid fa-arrow-up text-red-500"></i> Shipment Deduction
                                            @endif
                                        </div>
                                        @if($txn->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ $txn->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">
                                        @if($txn->type === 'credit')
                                            <span class="text-green-600">+ &#8377;{{ number_format($txn->amount, 2) }}</span>
                                        @else
                                            <span class="text-red-600">- &#8377;{{ number_format($txn->amount, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-700">
                                        &#8377;{{ number_format($txn->closing_balance ?? 0, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                                            <i class="fa-solid fa-receipt text-2xl"></i>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-900 mb-1">No transactions yet</h3>
                                        <p class="text-sm text-gray-500 font-medium">Recharge your wallet to start shipping.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tax Invoices -->
                <div x-show="view === 'invoices'" style="display: none;" class="p-16 text-center">
                    <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                        <i class="fa-solid fa-file-invoice text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">No Invoices Generated</h3>
                    <p class="text-sm text-gray-500 font-medium">Monthly tax invoices will appear here at the end of each billing cycle.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Load Cashfree V3 SDK -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

<script>
function walletManager() {
    return {
        amount: 1000,
        loading: false,
        view: 'transactions',
        
        async initiateRecharge() {
            if(this.amount < 100) {
                alert("Minimum recharge amount is ₹100");
                return;
            }
            this.loading = true;
            
            try {
                // Step 1: Create Order on Backend
                let response = await fetch('{{ route('seller.wallet.recharge') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: this.amount })
                });
                
                let data = await response.json();
                
                if(data.success) {
                    if(data.mock_mode) {
                        // Backend is in mock mode (No Cashfree keys). Auto-verify.
                        this.verifyRecharge(data.order_id, true);
                    } else {
                        // Load actual Cashfree Checkout
                        const cashfree = Cashfree({ mode: "sandbox" }); // Change to "production" in live
                        let checkoutOptions = {
                            paymentSessionId: data.payment_session_id,
                            redirectTarget: "_modal" // Opens in a modal overlay
                        };
                        cashfree.checkout(checkoutOptions).then((result) => {
                            if(result.error){
                                alert("Payment failed or cancelled.");
                                this.loading = false;
                            }
                            if(result.redirect){
                                console.log("Redirection.");
                            }
                            if(result.paymentDetails){
                                // Payment successful on frontend, verify on backend
                                this.verifyRecharge(data.order_id, false);
                            }
                        });
                    }
                } else {
                    alert(data.message || "Error initiating recharge.");
                    this.loading = false;
                }
            } catch(e) {
                alert("Failed to connect to payment server.");
                this.loading = false;
            }
        },
        
        async verifyRecharge(orderId, isMock) {
            try {
                let res = await fetch('{{ route('seller.wallet.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId, mock_mode: isMock })
                });
                let data = await res.json();
                if(data.success) {
                    window.location.reload();
                } else {
                    alert("Error verifying payment.");
                }
            } catch(e) {
                alert("Error verifying payment.");
            }
        }
    }
}
</script>
@endsection

