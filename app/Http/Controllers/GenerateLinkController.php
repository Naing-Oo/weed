<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\LinkTransaction;
use App\Models\Agent;
use App\Models\SubAgent;
use App\Models\Influencer;
use App\Models\Seller;
use Auth;
use Carbon\Carbon;

class GenerateLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:generate_link-list|generate_link-create|generate_link-edit|generate_link-delete', ['only' => ['index','show']]);
        $this->middleware('permission:generate_link-create', ['only' => ['create','store']]);
        $this->middleware('permission:generate_link-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:generate_link-delete', ['only' => ['destroy']]);
    }
    
    // List
    public function index(){

        $isAdmin = Auth::user()->is_admin;

        // if($isAdmin){
        //     $linkTransactions = LinkTransaction::all();
        // }
        // else{
        //     // $userId = Auth::user()->id;
        //     $roleName = DB::table('model_has_roles')
        //             ->where('model_has_roles.model_id', Auth::user()->id)
        //             ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
        //             ->first()->name;
                
        //     if($roleName == 'Agent'){
        //         $salesId = Agent::where('user_id',  Auth::user()->id)->first()->sales_id;
        //         $linkTransactions = LinkTransaction::where('sales_id', $salesId)->get();

        //     }elseif ($roleName == 'Sub Agent') {
        //         $salesId = SubAgent::where('user_id',  Auth::user()->id)->first()->sales_id;
        //         $linkTransactions = LinkTransaction::where('sales_id', $salesId)->get();
                
        //     }elseif ($roleName == 'Influencer') {
        //         $salesId = Influencer::where('user_id',  Auth::user()->id)->first()->sales_id;
        //         $linkTransactions = LinkTransaction::where('sales_id', $salesId)->get();
        //     }
            
        // }
        if($isAdmin){
            $linkTransactions = LinkTransaction::get();
        }else{
            $salesId  = Seller::where('user_id', Auth::user()->id)->first()->sales_id;
            $linkTransactions = LinkTransaction::where('sales_id', $salesId)->get();
        }
        
        return view('generate-link.index', compact('linkTransactions'));
    }

    // Create
    public function create(){

        $categories = DB::table('categories')->pluck('category_name_th', 'id')->all();
        $seller  = Seller::where('user_id', Auth::user()->id)
                           ->select('sales_id','name')->first();
        
        return view('generate-link.create', compact('seller', 'categories'));
    }


    // Detail
    public function show($id){
        
        $linkTransaction = LinkTransaction::where('id', $id)->first();
        
        return view('generate-link.details', compact('linkTransaction'));
    }

    // Edit
    public function edit($id){

        $linkTransaction = LinkTransaction::where('id', $id)->first();

        $categories = DB::table('categories')->pluck('category_name_th', 'id')->all();
        
        return view('generate-link.edit', compact('linkTransaction', 'categories'));
    }

    
    // Store
    public function store(Request $request){

        $request->validate([
            'sales_id' => 'required',
            'seller_name' => 'required',
            'product_id' => 'required',
            'link' => 'required'
        ]);

        $linkTransaction = new LinkTransaction();

        $linkTransaction->sales_id = $request->input('sales_id');
        $linkTransaction->seller_name = $request->input('seller_name');
        $linkTransaction->product_id = $request->input('product_id');
        $linkTransaction->product_name = $request->input('product_id');
        $linkTransaction->product_link = $request->input('link');
        $linkTransaction->created_by = Auth::user()->name;
        $linkTransaction->updated_by = Auth::user()->name;
        $linkTransaction->created_at = Carbon::now();
        $linkTransaction->updated_at = Carbon::now();

        $linkTransaction->save();

        session()->flash('message', 'Created the data successfully.');
        return redirect()->route('linkTransactions.index');

    }

    public function update(Request $request){
       
        $id = $request->input('id');

        $request->validate([
            'sales_id' => 'required',
            'seller_name' => 'required',
            'product_id' => 'required',
            'link' => 'required'
        ]);

        $linkTransaction = LinkTransaction::findOrFail($id);

        $linkTransaction->sales_id = $request->input('sales_id');
        $linkTransaction->seller_name = $request->input('seller_name');
        $linkTransaction->product_id = $request->input('product_id');
        $linkTransaction->product_name = $request->input('product_id');
        $linkTransaction->product_link = $request->input('link');
        $linkTransaction->updated_by = Auth::user()->name;
        $linkTransaction->updated_at = Carbon::now();

        $linkTransaction->save();

        session()->flash('message', 'Updated the data successfully.');
        return redirect()->route('linkTransactions.index');

    }


    // Delete
    public function destroy(Request $request){
       
        $id = $request->input('id');
         
        LinkTransaction::find($id)->delete();

        return response()->json('Deleted Successfully.', 200);
    }

    
    // Generate Link Function
    public function getLinkById(Request $request, $id){
        $id = $request->input('id');
        
        $link = 'http://127.0.0.1:8000/linkTransactions/commission/'.$id;
           
        return response()->json($link, 200);
    }


    // Redirect To Web Function
    public function commission(Request $request, $id){
           
        $salesId = $request->sales_id;
        
        if($id == 1){
            return redirect('http://127.0.0.1:9000/frontend/selling-courses?sales_id='. $salesId);
        }else{
            return redirect('http://127.0.0.1:9000/frontend/selling-courses?sales_id='. $salesId);
            
            
        }
        
    }


}
