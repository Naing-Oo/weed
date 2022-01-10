<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 

class AdminController extends Controller
{
    public function Index(){
        return view('admin.admin_login');   
    } // end mehtod 


    public function Dashboard(){
        return view('admin.home');
    } // end mehtod 

    public function Login(Request $request){
        $check = $request->all();
        if(Auth::guard('admin')
        ->attempt(['email' => $check['email'], 'password' => $check['password']])){
            return redirect()->route('admin.dashboard')
            ->with('error', 'Admin Login Successfully');
        }else{
            return back()->with('error', 'Invalid Email Or Password');
        }
            
    } // end mehtod 

    public function AdminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('login_form')->with('error','Admin Logout Successfully');

    } // end mehtod 

    public function AdminRegister(){

        return view('admin.admin_register');

    } // end mehtod 

    public function AdminRegisterCreate(Request $request){
        
        Admin::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now(),

        ]);

         return redirect()->route('login_form')->with('error','Admin Created Successfully');

    } // end mehtod 

}
