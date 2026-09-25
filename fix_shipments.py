import os

file_path = 'resources/views/seller/shipments.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    'href=\"{{ route(\'seller.label\', ->awb_number) }}\" target=\"_blank\" class=\"block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50\">Edit Order',
    'href=\"{{ route(\'seller.book\') }}?edit={{ ->id }}\" class=\"block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50\">Edit Order'
)

# Also let's append the alpine script and modals at the very bottom, replacing @endsection
modals_code = '''
    <!-- Update E-Way Bill Modal -->
    <div x-show="showEwayModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEwayModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showEwayModal = false"></div>
            
            <div x-show="showEwayModal" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-extrabold text-gray-900">Update E-Way Bill</h3>
                    <button @click="showEwayModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-3">Updating E-Way Bill for AWB: <span class="font-bold text-[#4338ca]" x-text="currentAwb"></span></p>
                    <label class="block text-xs font-bold text-gray-700 mb-1">E-Way Bill Number <span class="text-red-500">*</span></label>
                    <input type="text" x-model="ewayNumber" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca]" placeholder="e.g., 123456789012">
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button @click="showEwayModal = false" class="px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button @click="submitEway()" class="px-4 py-2 text-xs font-bold text-white bg-[#4338ca] rounded-lg hover:bg-indigo-700 transition">Save E-Way Bill</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Communication Modal -->
    <div x-show="showCommModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCommModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showCommModal = false"></div>
            
            <div x-show="showCommModal" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-extrabold text-gray-900">Communication Logs</h3>
                    <button @click="showCommModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-6">Tracking updates sent for AWB: <span class="font-bold text-[#4338ca]" x-text="currentAwb"></span></p>
                    
                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:ml-[2.5rem] md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        
                        <div class="relative flex items-center justify-between md:justify-normal">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-100 text-green-500 shadow-sm shrink-0 relative z-10 md:ml-5">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(100%-5rem)] ml-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="font-bold text-gray-900 text-xs">WhatsApp Sent</div>
                                    <time class="font-medium text-[10px] text-gray-500">Today, 10:30 AM</time>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-2">Your order has been shipped and is on the way. Tracking Link: ...</div>
                            </div>
                        </div>
                        
                        <div class="relative flex items-center justify-between md:justify-normal">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-500 shadow-sm shrink-0 relative z-10 md:ml-5">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(100%-5rem)] ml-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="font-bold text-gray-900 text-xs">Email Sent</div>
                                    <time class="font-medium text-[10px] text-gray-500">Yesterday, 5:45 PM</time>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-2">Order confirmed. We are packing your items...</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('shipmentActions', () => ({
            showEwayModal: false,
            showCommModal: false,
            currentAwb: '',
            ewayNumber: '',
            
            openEway(awb) {
                this.currentAwb = awb;
                this.ewayNumber = '';
                this.showEwayModal = true;
            },
            
            submitEway() {
                if(!this.ewayNumber) {
                    alert('Please enter an E-way bill number');
                    return;
                }
                alert('E-Way Bill ' + this.ewayNumber + ' successfully updated for ' + this.currentAwb);
                this.showEwayModal = false;
            },
            
            openComm(awb) {
                this.currentAwb = awb;
                this.showCommModal = true;
            }
        }));
    });
</script>
@endsection
'''

if '@endsection' in content:
    content = content.replace('@endsection', modals_code)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
