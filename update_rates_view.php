<?php
$file = __DIR__ . '/resources/views/admin/rates/index.blade.php';
$content = file_get_contents($file);

// Add to table headers
$content = str_replace('<th class="px-6 py-4">Zone Type</th>', '<th class="px-6 py-4">Zone Type</th><th class="px-6 py-4">Courier Partner</th>', $content);

// Add to table rows
$rowReplacement = <<<'HTML'
<td class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider">{{ $rate->zone_type }}</td>
<td class="px-6 py-4 font-bold text-blue-600">{{ $rate->courier ? $rate->courier->name : 'All / Generic' }}</td>
HTML;
$content = preg_replace('/<td class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider">{{ \$rate->zone_type }}<\/td>/i', $rowReplacement, $content);

// Add to Add Modal
$addModalSearch = <<<'HTML'
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Zone Type (e.g. Local, Regional, National)</label>
                                <input type="text" name="zone_type" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none uppercase">
                            </div>
HTML;
$addModalReplace = <<<'HTML'
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Zone Type (e.g. Local)</label>
                                    <input type="text" name="zone_type" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Specific Courier (Optional)</label>
                                    <select name="courier_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                        <option value="">-- Apply to All --</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
HTML;
$content = str_replace($addModalSearch, $addModalReplace, $content);

// Add to Edit Modal
$editModalSearch = <<<'HTML'
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
HTML;
$editModalReplace = <<<'HTML'
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Apply to Specific Courier (Optional)</label>
                                <select name="courier_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none" x-model="editData.courier_id">
                                    <option value="">-- Apply to All --</option>
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
HTML;
$content = str_replace($editModalSearch, $editModalReplace, $content);

// Update Alpine editData script
$alpineSearch = <<<'HTML'
@click="showEditModal = true; editData = {
HTML;
$alpineReplace = <<<'HTML'
@click="showEditModal = true; editData = { courier_id: '{{ $rate->courier_id }}', 
HTML;
$content = str_replace($alpineSearch, $alpineReplace, $content);

file_put_contents($file, $content);
echo "Updated rates view.\n";
