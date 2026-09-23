<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AdminPickupController extends Controller {
    public function index() {
        return view('admin.pickups.index');
    }
}
