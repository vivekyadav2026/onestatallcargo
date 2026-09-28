<?php
$content = file_get_contents("app/Http/Controllers/RiderAppController.php");

$target = '$shipment = Shipment::where(\'awb_number\', $validated[\'awb_number\'])->first();';
$replacement = '
        $shipment = Shipment::where(\'awb_number\', $validated[\'awb_number\'])
            ->where(\'rider_id\', Auth::id())
            ->lockForUpdate()
            ->first();
';

$content = str_replace($target, $replacement, $content);

// Also add a DB::transaction wrapper to uploadEvidence
$targetFuncStart = 'public function uploadEvidence(Request $request)
    {';
    
$replacementFuncStart = 'public function uploadEvidence(Request $request)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {';

$content = str_replace($targetFuncStart, $replacementFuncStart, $content);

// Find the end of uploadEvidence and close the transaction
$targetFuncEnd = 'return back()->with(\'success\', $validated[\'action_type\'] . \' complete! Evidence uploaded for \' . $validated[\'awb_number\']);
    }';
    
$replacementFuncEnd = 'return back()->with(\'success\', $validated[\'action_type\'] . \' complete! Evidence uploaded for \' . $validated[\'awb_number\']);
        });
    }';

$content = str_replace($targetFuncEnd, $replacementFuncEnd, $content);

file_put_contents("app/Http/Controllers/RiderAppController.php", $content);
