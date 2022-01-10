<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 

class SellerController extends Controller
{
    public function SellerIndex(){
        return view('seller.seller_login');
    } // END METHOD 


    public function SellerDashboard(){
        return view('seller.home');
    }// END METHOD 


 public function SellerLogin(Request $request){
        // dd($request->all());

        $check = $request->all();
        if(Auth::guard('seller')->attempt(['email' => $check['email'], 'password' => $check['password']  ])){
            return redirect()->route('seller.dashboard')->with('error','Seller Login Successfully');
        }else{
            return back()->with('error','Invaild Email Or Password');
        }

    } // end mehtod 


    public function SellerLogout(){

         Auth::guard('seller')->logout();
        return redirect()->route('seller_login_form')->with('error','Seller Logout Successfully');
    } // end mehtod 


    public function SellerRegister(){
        return view('seller.seller_register');
    }// end mehtod 


    public function SellerRegisterCreate(Request $request){

        // dd($request->all());

        Seller::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now(),

        ]);

         return redirect()->route('seller_login_from')->with('error','Seller Created Successfully');

    } // end mehtod 



} 
