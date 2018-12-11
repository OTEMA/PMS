<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller {

    public function login(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->input();
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '1'])) {
                echo"Admin logged in";
            } else if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '2'])) {
                echo"Accountant logged in";
            } else if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '3'])) {
                echo"Super Admin logged in";
            } Else {
                Echo'User not found';
            }
        }
        return view('admin.adminlogin');
    }

}
