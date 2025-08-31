<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    
    public function setPlayerTest() {
        return redirect()->route('start');
    }
    


}
