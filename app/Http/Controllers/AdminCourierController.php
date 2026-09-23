<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AdminCourierController extends Controller {
    public function index() {
        return view('admin.couriers.index');
    }
}
