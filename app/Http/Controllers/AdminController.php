<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

class AdminController extends Controller {

    public function login(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->input();
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '1'])) {
                //Load the admin dashboard
                Session::put('adminSession', $data['email']);
                return redirect('/admin/dashboard')->with('flash_message_success', 'Login Successfull');
            } else if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '2'])) {
                Session::put('accountSession', $data['email']);
                return redirect('/accounts/dashboard')->with('flash_message_success', 'Login Successfull');
            } else if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => '3'])) {
                Session::put('superadminSession', $data['email']);
                return redirect('/superadmin/dashboard')->with('flash_message_success', 'Login Successfull');
            } Else {
                Echo'User not found';
            }
        }
        return view('admin.adminlogin');
    }

    public function dashboard() {
        if (Session::has('adminSession')) {
            return view('admin.dashboard');
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function accountsDashboard() {
        if (Session::has('accountSession')) {
            return view('accounts.dashboard');
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function sAdminDashboard() {
        if (Session::has('superadminSession')) {
            return view('superadmin.dashboard');
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function settings() {
        if (Session::has('adminSession')) {
            return view('admin.settings');
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function chkPassword(Request $request) {
        if (Session::has('adminSession')) {
            $data = $request->all();
            $current_password = $data['current_pwd'];
            $check_password = User::where(['role' => '1'])->first();
            if (Hash::check($current_password, $check_password->password)) {
                echo "true";
                die;
            } else {
                echo "false";
                die;
            }
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function updatePassword(Request $request) {
        if (Session::has('adminSession')) {
            if ($request->isMethod('post')) {
                $data = $request->all();
                $check_password = User::where(['email' => Auth::user()->email])->first();
                $current_password = $data['current_pwd'];
                $email = Auth::user()->email;
                Hash::check($current_password, $check_password->password);
                $password = bcrypt($data['new_pwd']);
                User::where('email', $email)->update(['password' => $password]);
                Session::flush();
                return redirect('/admin')->with('flash_message_success', 'Password Updated Successfully');
            }
        } else {
            return redirect('/admin')->with('flash_message_error', 'Access denied! Please Login first');
        }
    }

    public function logout() {
        Session::flush();
        return redirect('/admin')->with('flash_message_success', 'Logged out Successfully');
    }

}
