<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\RealTimeMessage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function send()
    {
        event(new RealTimeMessage('Real time message send.'));
        return 'send message real time notification';
    }
}
