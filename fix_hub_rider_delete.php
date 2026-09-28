<?php
$content = file_get_contents("app/Http/Controllers/HubRiderController.php");

$destroyMethod = '
    public function destroy($id)
    {
        $scope = $this->getAuthScope();
        $rider = Rider::with("user")->findOrFail($id);
        
        if ($scope["franchise_id"]) {
            if ($rider->franchise_id !== $scope["franchise_id"]) abort(403, "UNAUTHORIZED");
        } else {
            if ($rider->franchise_id !== null || !in_array($rider->hub_id, $scope["hubs"]->pluck("id")->toArray())) {
                abort(403, "UNAUTHORIZED");
            }
        }

        // Delete the user record, which cascades to delete the rider record
        $rider->user->delete();

        return back()->with("success", "Rider deleted successfully.");
    }
}';

$content = preg_replace('/}\s*$/', $destroyMethod, $content);
file_put_contents("app/Http/Controllers/HubRiderController.php", $content);
