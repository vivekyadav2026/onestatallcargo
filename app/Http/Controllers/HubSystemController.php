<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HubSystemController extends Controller
{
    public function whatsapp()
    {
        return view('hub.whatsapp.index');
    }

    public function support()
    {
        return view('hub.support.index');
    }

    public function notifications()
    {
        return view('hub.notifications.index');
    }
}
