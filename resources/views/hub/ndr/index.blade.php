@extends('layouts.hub')

@section('content')
<div class="space-y-6" x-data="{ actionModal: false, selectedId: null, selectedAwb: '' }">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">NDR Management</h2>
    </div>

    <!-- NDR Action Modal -->
    <div x-show="actionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="actionModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="actionModal = false"></div>
            <div x-show="actionModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`/hub/ndr/${selectedId}/action`" method="POST" x-data="{ actionType: 'reattempt' }">
                    @csrf
                    <div class="px-6 py-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">NDR Action: <span x-text="selectedAwb" class="text-[var(--gold)]"></span></h3>
                            <button type="button" @click="actionModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Select Action</label>
                                <select name="action" x-model="actionType" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                    <option value="reattempt">Schedule Reattempt</option>
                                    <option value="rto">Mark as RTO (Return to Origin)</option>
                                </select>
                            </div>
                            <div x-show="actionType === 'reattempt'">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Assign Rider for Reattempt</label>
                                <select name="rider_id" :required="actionType === 'reattempt'" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                    <option value="">-- Select Rider --</option>
                                    @foreach($riders as $rider)
                                        <option value="{{ $rider->id }}">{{ $rider->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Remarks / Reason</label>
                                <textarea name="remarks" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="Optional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="actionModal = false" class="px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-gray-900 hover:bg-black rounded-lg">Submit Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-800">Pending NDR Shipments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">AWB</th>
                        <th class="px-4 py-3">Failed Reason</th>
                        <th class="px-4 py-3">Receiver</th>
                        <th class="px-4 py-3">Last Rider</th>
                        <th class="px-4 py-3">Last Attempt</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $shipment->awb_number }}</td>
                        <td class="px-4 py-3 text-red-600 font-bold text-xs">{{ $shipment->ndr_reason ?? 'Unknown Reason' }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $shipment->receiver_name }}</div>
                            <div class="text-xs text-gray-500">{{ $shipment->receiver_phone }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            {{ $shipment->rider_id ? optional(\App\Models\User::find($shipment->rider_id))->name ?? 'Deleted' : 'None' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $shipment->updated_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button @click="selectedId = {{ $shipment->id }}; selectedAwb = '{{ $shipment->awb_number }}'; actionModal = true" class="text-[var(--gold-deep)] font-bold hover:underline text-xs bg-yellow-50 px-3 py-1.5 rounded-lg border border-yellow-200">
                                Take Action
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No NDR shipments pending action.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 border-t border-gray-100">{{ $shipments->links() }}</div>
        </div>
    </div>
</div>
@endsection
