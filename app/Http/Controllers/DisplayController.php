<?php

namespace App\Http\Controllers;

use App\Display;
use DB;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO:: 今はダイレクトにDB取得しているが既に取得メソッドを記載しているのでそちらから引っ張るようにする。
        $imageInfo = DB::table('products')->get();
        return view('/display/index');
    }
}
