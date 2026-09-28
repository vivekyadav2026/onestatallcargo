<?php
$content = file_get_contents("resources/views/hub/fleet/index.blade.php");

// 1. Add viewRider to Alpine x-data
$content = str_replace('x-data="{ showAddRider: false, editRider: null }"', 'x-data="{ showAddRider: false, editRider: null, viewRider: null }"', $content);

// 2. Add View button
$buttonsTarget = '<td class="px-4 py-3 text-right flex justify-end gap-2">
                            <button @click="editRider = {{ $rider->id }}"';
$buttonsReplacement = '<td class="px-4 py-3 text-right flex justify-end gap-2">
                            <button @click="viewRider = {{ $rider->id }}" class="text-gray-600 font-bold hover:underline text-xs bg-gray-100 px-2 py-1 rounded">View</button>
                            <button @click="editRider = {{ $rider->id }}"';
$content = str_replace($buttonsTarget, $buttonsReplacement, $content);

// 3. Add View Modal HTML after the Edit Modal HTML
$editModalEnd = '</form>
                            </div>
                        </div>
                    </div>';

$viewModalHtml = '
                    <!-- View Modal for this Rider -->
                    <div x-show="viewRider === {{ $rider->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                            <div x-show="viewRider === {{ $rider->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="viewRider = null"></div>
                            <div x-show="viewRider === {{ $rider->id }}" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="px-6 py-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900">Rider Profile: {{ $rider->user->name }}</h3>
                                        <button type="button" @click="viewRider = null" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-2xl font-bold">
                                                {{ substr($rider->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">{{ $rider->user->name }}</div>
                                                <div class="text-sm text-gray-500 uppercase font-bold">{{ str_replace(\'_\', \' \', $rider->user->role) }}</div>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $rider->status === \'active\' ? \'bg-green-100 text-green-700\' : \'bg-red-100 text-red-700\' }}">
                                                    {{ strtoupper($rider->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Contact Information</div>
                                                <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-phone mr-2 text-gray-400"></i>{{ $rider->user->phone }}</div>
                                                <div class="text-sm text-gray-600"><i class="fa-solid fa-envelope mr-2 text-gray-400"></i>{{ $rider->user->email }}</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Vehicle Details</div>
                                                <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-motorcycle mr-2 text-gray-400"></i>{{ $rider->vehicle_type ?? \'N/A\' }}</div>
                                                <div class="text-sm text-gray-600"><i class="fa-solid fa-id-card mr-2 text-gray-400"></i>{{ $rider->vehicle_number ?? \'N/A\' }}</div>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Hub Assignment</div>
                                            <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-building mr-2 text-gray-400"></i>{{ $rider->hub->name ?? \'Unassigned\' }}</div>
                                            <div class="text-xs text-gray-500 mt-1">Joined: {{ $rider->created_at->format(\'d M Y, h:i A\') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                                    <button type="button" @click="viewRider = null" class="px-5 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>';

$content = str_replace($editModalEnd, $editModalEnd . "\n" . $viewModalHtml, $content);

file_put_contents("resources/views/hub/fleet/index.blade.php", $content);
