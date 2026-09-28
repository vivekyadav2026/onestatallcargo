<?php
// Update Admin View
$contentAdmin = file_get_contents("resources/views/admin/riders/index.blade.php");

$targetAdmin = '                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                        <input type="text" name="phone" value="{{ optional($rider->user)->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                    </div>';
                                                    
$replacementAdmin = '                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                            <input type="text" name="phone" value="{{ optional($rider->user)->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Reset Password</label>
                                                            <input type="text" name="password" placeholder="Leave blank to keep current" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                        </div>
                                                    </div>';

$contentAdmin = str_replace($targetAdmin, $replacementAdmin, $contentAdmin);
file_put_contents("resources/views/admin/riders/index.blade.php", $contentAdmin);

// Update Hub View
$contentHub = file_get_contents("resources/views/hub/fleet/index.blade.php");

$targetHub = '                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                <input type="text" name="phone" value="{{ $rider->user->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                            </div>';

$replacementHub = '                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                    <input type="text" name="phone" value="{{ $rider->user->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Reset Password</label>
                                                    <input type="text" name="password" placeholder="Leave blank to keep current" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                </div>
                                            </div>';
                                            
$contentHub = str_replace($targetHub, $replacementHub, $contentHub);
file_put_contents("resources/views/hub/fleet/index.blade.php", $contentHub);
