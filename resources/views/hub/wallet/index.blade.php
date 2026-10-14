@extends('layouts.hub')

@section('content')
@php
    $kycApproved = Auth::user()->isKycApproved();
@endphp

<div class="space-y-6" x-data="hubWalletManager()">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Wallet & Payouts</h2>
            <p class="text-xs text-gray-500 mt-1">Manage your prepaid wallet balance, add funds via Cashfree, and track transaction history.</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-2xl border border-green-200 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-600"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-2xl border border-red-200 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-600"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Wallet Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Current Balance -->
        <div class="bg-gradient-to-br from-[#0f172a] to-[#1e1b4b] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-10 -mt-10"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-[#FFD700] opacity-20 rounded-full blur-xl -ml-10 -mb-10"></div>
            
            <div class="relative z-10">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-[var(--gold)]"></i> Available Balance
                </div>
                <div class="text-3xl lg:text-4xl font-extrabold text-[var(--gold)] tracking-tight mb-3">
                    ₹{{ number_format($user->wallet_balance ?? 0, 2) }}
                </div>
                <p class="text-xs text-gray-400">Prepaid balance used for shipment freight deductions.</p>
            </div>
        </div>
        
        <!-- Total Earnings -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-sack-dollar text-emerald-600"></i> Total Hub Commission
                </div>
                <div class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">
                    ₹{{ number_format($totalEarnings ?? 0, 2) }}
                </div>
                <div class="text-xs text-gray-400 font-medium">(All Time Credited Earnings)</div>
            </div>
            @if(isset($pendingEarnings) && $pendingEarnings > 0)
                <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-amber-600 font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-clock-rotate-left"></i> ₹{{ number_format($pendingEarnings, 2) }} pending settlement
                </div>
            @endif
        </div>

        <!-- Pending COD Remittance -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-dollar text-red-500"></i> Pending COD Remittance
                </div>
                <div class="text-3xl font-extrabold text-red-600 tracking-tight mb-1">
                    ₹{{ number_format($pendingCod ?? 0, 2) }}
                </div>
                <div class="text-xs text-gray-400 font-medium">To be collected & remitted to Admin</div>
            </div>
            @if(isset($pendingCod) && $pendingCod > 0)
            <form action="{{ route('hub.wallet.remit') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-black transition shadow-sm">
                    Remit COD to Admin
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Main Section: Recharge (Left) + Transactions (Right) -->
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left: Recharge Box -->
        <div class="w-full lg:w-1/3 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Recharge Wallet</h3>
                        <p class="text-[11px] text-gray-400">Instant credit via UPI, Cards, NetBanking</p>
                    </div>
                </div>

                @if(!$kycApproved)
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-lock text-xl"></i>
                        </div>
                        <h4 class="font-extrabold text-amber-900 text-sm mb-1.5">KYC Verification Required</h4>
                        <p class="text-xs text-amber-700 mb-4 leading-relaxed">
                            Your Franchise KYC must be approved by the Admin before you can add funds to your wallet or book shipments.
                        </p>
                        <a href="{{ route('hub.dashboard') }}" class="inline-block px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-black transition shadow-sm">
                            <i class="fa-solid fa-id-card mr-1.5"></i> Complete KYC
                        </a>
                    </div>
                @else
                    <form @submit.prevent="initiateRecharge">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Enter Amount (Min ₹500)</label>
                        <div class="relative mb-4">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-lg">₹</span>
                            <input type="number" x-model.number="amount" min="500" step="1" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-extrabold text-gray-900 text-lg transition" required>
                        </div>

                        <!-- Quick Chips -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <button type="button" @click="amount = 500" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition">+ ₹500</button>
                            <button type="button" @click="amount = 1000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition">+ ₹1,000</button>
                            <button type="button" @click="amount = 2000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition">+ ₹2,000</button>
                            <button type="button" @click="amount = 5000" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition">+ ₹5,000</button>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-gray-900 hover:bg-black text-white font-extrabold rounded-xl text-sm transition shadow-sm flex justify-center items-center gap-2 disabled:opacity-50" :disabled="loading">
                            <span x-show="!loading"><i class="fa-solid fa-credit-card mr-1"></i> Proceed to Pay</span>
                            <span x-show="loading"><i class="fa-solid fa-circle-notch fa-spin"></i> Processing...</span>
                        </button>
                        
                        <div class="mt-4 flex items-center justify-center gap-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo-vector.svg" class="h-4 opacity-70 grayscale" alt="UPI">
                            <i class="fa-brands fa-cc-visa text-gray-400 text-xl"></i>
                            <i class="fa-brands fa-cc-mastercard text-gray-400 text-xl"></i>
                            <span class="text-[10px] text-gray-400 font-semibold ml-1">Secured by Cashfree</span>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right: Transactions Ledger -->
        <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Transaction History</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Wallet recharges, booking deductions and adjustments</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3">Txn ID / Date</th>
                                <th class="px-5 py-3">Description</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3 text-right">Amount</th>
                                <th class="px-5 py-3 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @if(isset($transactions) && count($transactions) > 0)
                                @foreach($transactions as $txn)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-gray-900 font-mono">{{ $txn->reference_id ?? 'TXN-'.$txn->id }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 max-w-xs truncate">
                                        <div class="font-semibold text-gray-800">{{ $txn->description ?? 'Transaction' }}</div>
                                        <div class="text-[10px] text-gray-400 capitalize">Status: {{ $txn->status }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $txn->type === 'credit' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ strtoupper($txn->type) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-sm {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-gray-900 text-sm">
                                        ₹{{ number_format($txn->balance_after ?? 0, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2 text-xl">
                                            <i class="fa-solid fa-receipt"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 text-sm">No transactions yet</p>
                                        <p class="text-xs text-gray-400 mt-1">Recharge your wallet to start booking shipments from your hub.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if(isset($transactions) && method_exists($transactions, 'links') && $transactions->hasPages())
                    <div class="p-3.5 border-t border-gray-100 bg-gray-50/50">{{ $transactions->links() }}</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Cashfree V3 SDK -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

<script>
function hubWalletManager() {
    return {
        amount: 500,
        loading: false,
        
        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('order_id');
            if (orderId) {
                this.verifyRecharge(orderId, false);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        },
        
        async initiateRecharge() {
            if (this.amount < 500) {
                alert("Minimum recharge amount is ₹500");
                return;
            }
            this.loading = true;
            
            try {
                const response = await fetch('{{ route('hub.wallet.recharge') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: this.amount })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (data.mock_mode) {
                        // Test/Mock mode: auto-verify
                        await this.verifyRecharge(data.order_id, true);
                    } else {
                        // Cashfree Modal Checkout
                        const cashfree = Cashfree({ mode: data.environment || "sandbox" });
                        const checkoutOptions = {
                            paymentSessionId: data.payment_session_id,
                            redirectTarget: "_modal"
                        };
                        cashfree.checkout(checkoutOptions).then((result) => {
                            if (result.error) {
                                alert("Payment failed or cancelled: " + (result.error.message || ''));
                                this.loading = false;
                            }
                            if (result.paymentDetails) {
                                this.verifyRecharge(data.order_id, false);
                            }
                        });
                    }
                } else {
                    alert(data.message || "Error initiating recharge.");
                    this.loading = false;
                }
            } catch (e) {
                alert("Failed to connect to payment server: " + e.message);
                this.loading = false;
            }
        },
        
        async verifyRecharge(orderId, isMock) {
            try {
                const res = await fetch('{{ route('hub.wallet.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId, mock_mode: isMock })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || "Error verifying payment.");
                    this.loading = false;
                }
            } catch (e) {
                alert("Error verifying payment.");
                this.loading = false;
            }
        }
    };
}
</script>
@endsection
