<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AdminBillingController extends Controller {
    public function index() {
        return view('admin.billing.index');
    }
}
