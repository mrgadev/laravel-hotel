<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointUserController extends Controller
{
    public function index() {
        return view('dashboard.user.point.index');
    }

    public function show() {
        return view('dashboard.user.point.detail');
    }
}
