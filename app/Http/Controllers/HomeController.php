<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function userInfo()
    {
        $userMasterData = DB::table('users')->get();
        return view('admin.user', compact('userMasterData'));
    }
    
    public function productInfo()
    {
        $productMasterData = DB::table('products')->get();
        return view('admin.product', compact('productMasterData'));
    }
}
