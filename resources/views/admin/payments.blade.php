@extends('layouts.admin')

@section('title', 'Payments & Transactions - Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Transactions & Revenue</h1>
            <p class="text-xs" style="color: var(--text-muted);">प्लेटफ़ॉर्म पर हुए सभी भुगतानों का प्रबंधन</p>
            <div class="mt-4">
                <form action="{{ route('admin.payments') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order ID or email..." class="text-xs p-2 rounded border" style="background-color: var(--bg-main); border-color: var(--border-hard); width: 250px;">
                    <button type="submit" class="btn btn-gold text-xs px-3 shadow-md">Search</button>
                </form>
            </div>
        </div>

        <div class="px-6 py-3 rounded-2xl border shrink-0" style="background-color: var(--teal-soft); border-color: var(--teal); color: var(--teal);">
            <div class="text-[10px] font-extrabold uppercase tracking-wider">Total Revenue</div>
            <div class="text-xl font-black">₹{{ number_format($totalRevenue) }}</div>
        </div>
    </div>

    <div class="p-6 rounded-3xl panel space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">Order ID / Ref</th>
                        <th class="p-3.5">अभ्यर्थी (Student)</th>
                        <th class="p-3.5">रकम (Amount)</th>
                        <th class="p-3.5">माध्यम (Method)</th>
                        <th class="p-3.5">स्थिति (Status)</th>
                        <th class="p-3.5">दिनांक (Date)</th>
                        <th class="p-3.5">एक्शन</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($payments as $p)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5">
                                <span class="font-bold block" style="color: var(--text-main) !important;">{{ $p->order_id }}</span>
                                <span class="text-[10px] font-mono" style="color: var(--text-muted);">{{ $p->transaction_ref }}</span>
                            </td>
                            <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">{{ $p->user->name ?? 'Deleted User' }}</td>
                            <td class="p-3.5 font-black" style="color: var(--gold-deep);">₹{{ $p->amount }}</td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ $p->payment_method }}</td>
                            <td class="p-3.5">
                                @if($p->status === 'PAID')
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                        PAID (PRO ACTIVE)
                                    </span>
                                @elseif($p->status === 'PENDING')
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--gold-soft); color: var(--gold-deep); border: 1px solid var(--gold);">
                                        PENDING
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--rose-soft); color: var(--rose); border: 1px solid var(--rose);">
                                        {{ $p->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ $p->created_at->format('d M Y, h:i A') }}</td>
                            <td class="p-3.5 flex items-center gap-2">
                                <form action="{{ route('admin.payments.update', $p->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="text-[10px] p-1 border rounded" style="background-color: var(--bg-main); border-color: var(--border-hard);" onchange="this.form.submit()">
                                        <option value="PAID" {{ $p->status == 'PAID' ? 'selected' : '' }}>PAID</option>
                                        <option value="PENDING" {{ $p->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                        <option value="FAILED" {{ $p->status == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                                    </select>
                                </form>
                                <form action="{{ route('admin.payments.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Delete this payment record permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--rose-soft); border-color: var(--rose); color: var(--rose);" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
