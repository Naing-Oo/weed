<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Influencer;
use App\Models\Agent;
use App\Models\SubAgent;
use App\Models\User;
use App\Models\Seller;
// use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Auth;
use Carbon\Carbon;

class InfluencerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:influencer-list|influencer-create|influencer-edit|influencer-delete', ['only' => ['index','store']]);
        $this->middleware('permission:influencer-create', ['only' => ['create','store']]);
        $this->middleware('permission:influencer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:influencer-delete', ['only' => ['destroy']]);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isAdmin = Auth::user()->is_admin;

        if($isAdmin){
            $influencers = Influencer::select('*')
            ->orderBy('created_at', 'desc')
            ->get();
        }
        else{
            // $roleName = Seller::where('user_id', Auth::user()->id)->first()->role_name;
            // UAT Duration
            $roleName = Seller::where('user_id', Auth::user()->id)
                  ->select('role_name as name')->first();
            if($roleName == null){
                $roleName = DB::table('model_has_roles')
                    ->where('model_has_roles.model_id', Auth::user()->id)
                    ->select('name')
                    ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
                    ->first();
            }
            // UAT Duration
                
            if($roleName->name == 'Agent'){

                $agentId = Agent::where('user_id', Auth::user()->id)->first()->id;                
                $influencers = Influencer::where('agent_id', $agentId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

            }elseif ($roleName->name == 'Sub Agent') {

                $subAgentId = SubAgent::where('user_id', Auth::user()->id)->first()->id;
                $influencers = Influencer::where('sub_agent_id', $subAgentId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                
            }elseif ($roleName->name == 'Influencer') {

                $influencers = Influencer::where('user_id', Auth::user()->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            }
            
        }

        return view('influencer.index')->with(compact('influencers'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = DB::table('roles')->where('name', 'Influencer')
                ->select('id', 'name', 'commission_rate')->get();
        $agents = Agent::select('id', 'user_id', 'full_name')->get();
        $subAgents = SubAgent::select('id', 'user_id', 'full_name')->get();
        
        return view('influencer.create', compact('roles', 'agents', 'subAgents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'commission_rate' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required',
            'agent_id' => 'required',
            'sub_agent_id' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required',
            'agent_id.required' => 'Agent is required',
            'sub_agent_id.required' => 'Sub Agent is required',
        ]);
        
        if($validator->fails()){
            return redirect()->route('influencers.create')
            ->withErrors($validator)
            ->withInput($request->except('roles'));
        }
        
        $user = new User();
        $user->name = $request->input('first_name') . ' ' . $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        $user->save();
        $userId = $user->id;
        $user->assignRole($request->input('roles'));

        $influencer = new Influencer();
        // $agentId = Helper::IDGenerator($agent, 'agent_id', 5, 'AGT'); /** Generate id */
        $uniqueId = uniqid();
         
        $influencer->agent_id =  $request->input('agent_id');
        $influencer->sub_agent_id =  $request->input('sub_agent_id');
        $influencer->user_id = $userId;
        $influencer->sales_id = $uniqueId;
        $influencer->first_name = $request->input('first_name');
        $influencer->last_name = $request->input('last_name');
        $influencer->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $influencer->address = $request->input('address');
        $influencer->email = $request->input('email');
        $influencer->phone = $request->input('phone');
        $influencer->join_date =$request->input('join_date');
        $influencer->resign_date = $request->input('resign_date');
        $influencer->commission_rate = $request->input('commission_rate');
        $influencer->remark = $request->input('remark');
        $influencer->created_by = Auth::user()->name;
        $influencer->updated_by = Auth::user()->name;
        $influencer->created_at = Carbon::now();
        $influencer->updated_at = Carbon::now();
        $influencer->save();

        $seller = new Seller();

        $seller->sales_id = $uniqueId;
        $seller->user_id = $userId;
        $seller->role_name =  $request->input('roles')[0];
        $seller->name = $request->input('first_name') . ' ' . $request->input('last_name');
        $seller->address = $request->input('address');
        $seller->phone = $request->input('phone');
        $seller->email = $request->input('email');
        $seller->password = Hash::make($request->input('password'));
        $seller->commission_rate = $request->input('commission_rate');
        $seller->created_at = Carbon::now();
        $seller->updated_at = Carbon::now();
        $seller->save();

        session()->flash('message', 'Created the data successfully.');
        return redirect()->route('influencers.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $influencer = Influencer::find($id);

        return view('influencer.details')->with(compact('influencer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $isAdmin = Auth::user()->is_admin;
        
        if($isAdmin){
            $influencer = Influencer::find($id);
        }else{
            $influencer = Influencer::where('user_id', Auth::user()->id )->first();
        }
        if($influencer == null){
            return 'Bad Request';
        }
        
        $user = User::where('id', $influencer->user_id)->first();
        
        $roles = DB::table('roles')->where('name', 'Influencer')
            ->select('id', 'name', 'commission_rate')->get();
        $userRole = $user->roles->pluck('name','name')->all();
        $agents = Agent::select('id', 'full_name')->get();
        
        return view('influencer.edit')->with(compact('influencer', 'roles', 'userRole', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'commission_rate' => 'required',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required',
            'agent_id' => 'required',
            'sub_agent_id' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required',
            'agent_id.required' => 'Agent is required',
            'sub_agent_id.required' => 'Sub Agent is required'
        ]);

        if($validator->fails()){
            return redirect()->route('influencers.edit', $id)
                            ->withErrors($validator)
                            ->withInput($request->except('roles'));
        }

        // $input = $request->all();
        // if(!empty($input['password'])){ 
        //     $input['password'] = Hash::make($input['password']);
        // }else{
        //     $input = Arr::except($input,array('password'));    
        // }
    
        $influencer = Influencer::find($id);
         
        $influencer->agent_id = $request->input('agent_id');
        $influencer->sub_agent_id = $request->input('sub_agent_id');
        $influencer->first_name = $request->input('first_name');
        $influencer->last_name = $request->input('last_name');
        $influencer->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $influencer->address = $request->input('address');
        $influencer->phone = $request->input('phone');
        $influencer->join_date =$request->input('join_date');
        $influencer->resign_date = $request->input('resign_date');
        $influencer->commission_rate = $request->input('commission_rate');
        $influencer->remark = $request->input('remark');
        $influencer->updated_by = Auth::user()->name;
        $influencer->updated_at = Carbon::now();

        $influencer->save();
        $userId = $influencer->user_id;

        // Just for UAT Duration
        $oldSeller = Seller::where('user_id', $userId)->first();
        if($oldSeller == null){

            $seller = new Seller();

            $seller->sales_id = $influencer->sales_id;
            $seller->user_id = $userId;
            $seller->role_name = $request->input('roles')[0];
            $seller->name = $request->input('first_name') . ' ' . $request->input('last_name');
            $seller->address = $request->input('address');
            $seller->phone = $request->input('phone');
            $seller->email = $request->input('email');
            $seller->password = Hash::make($request->input('password'));
            $seller->commission_rate = $request->input('commission_rate');
            $seller->created_at = Carbon::now();
            $seller->updated_at = Carbon::now();
            $seller->save();
        }else{
            Seller::where('user_id', $userId)->update([
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'role_name' => $request->input('roles')[0],
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'commission_rate' => $request->input('commission_rate'),
                'updated_at' => Carbon::now(),
            ]);
        }
        

        $user = User::find($userId);
        $user->name = $request->input('first_name') . ' ' . $request->input('last_name');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        DB::table('model_has_roles')->where('model_id',$userId)->delete();
        $user->assignRole($request->input('roles'));
        
        session()->flash('message', 'Updated the data successfully.');
        return redirect()->route('influencers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        Influencer::find($id)->delete();        
        return response()->json('Delete record successfully!', 200);
    }

    public function getCommissionRate(Request $request){
        $roleName = $request->input('roleName');
        $role = DB::table('roles')
        ->where('name', $roleName)
        ->select('commission_rate')->first();

        return response()->json($role, 200);
    }

    public function getSubAgentByAgentId(Request $request, $id){
        $id = $request->input('id');
        $subAgents = SubAgent::where('agent_id', $id)->get();

        return response()->json($subAgents, 200);
    }

    // public function getAgentBySubAgentId(Request $request, $id){
        
    //     $id = $request->input('id');
    //     $agent = Agent::where('id', $id)->select('id', 'full_name')->first();

    //     return response()->json($agent, 200);
    // }
}
