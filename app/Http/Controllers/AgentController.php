<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agent;
use App\Models\Seller;
use App\Models\User;
// use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Auth;
use Carbon\Carbon;

class AgentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:agent-list|agent-create|agent-edit|agent-delete', ['only' => ['index','show']]);
        $this->middleware('permission:agent-create', ['only' => ['create','store']]);
        $this->middleware('permission:agent-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:agent-delete', ['only' => ['destroy']]);

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
            $agents = Agent::select('*')
            ->orderBy('created_at', 'desc')
            ->get();
        }else{
            $agents = Agent::where('user_id', Auth::user()->id)
            ->select('*')
            ->orderBy('created_at', 'desc')
            ->get();
        }
        if($agents == null){
            return 'Not Found';
        }

        return view('agent.index')->with(compact('agents'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = DB::table('roles')->where('name', 'Agent')
               ->select('id', 'name', 'commission_rate')->get();
        // $roles = Role::pluck('name','id')->all();
        
        return view('agent.create', compact('roles'));
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
            'roles' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required'
        ]);
        
        if($validator->fails()){
            return redirect()->route('agents.create')
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

        $agent = new Agent();
        // $agentId = Helper::IDGenerator($agent, 'agent_id', 5, 'AGT'); /** Generate id */
        $uniqueId = uniqid();
         
        $agent->user_id = $userId;
        $agent->sales_id = $uniqueId;
        $agent->first_name = $request->input('first_name');
        $agent->last_name = $request->input('last_name');
        $agent->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $agent->address = $request->input('address');
        $agent->email = $request->input('email');
        $agent->phone = $request->input('phone');
        $agent->join_date =$request->input('join_date');
        $agent->resign_date = $request->input('resign_date');
        $agent->commission_rate = $request->input('commission_rate');
        $agent->remark = $request->input('remark');
        $agent->created_by = Auth::user()->name;
        $agent->updated_by = Auth::user()->name;
        $agent->created_at = Carbon::now();
        $agent->updated_at = Carbon::now();
        
        $agent->save();

        $seller = new Seller();

        $seller->sales_id = $uniqueId;
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
          

        session()->flash('message', 'Created the data successfully.');
        return redirect()->route('agents.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isAdmin = Auth::user()->is_admin;

        if($isAdmin){
            $agent = Agent::find($id);
        }else{
            $agent = Agent::find(Auth::user()->id );
        }
        if($agent == null){
            return 'Not Found';
        }

        return view('agent.details')->with(compact('agent'));
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
            $agent = Agent::find($id);
        }else{
            $agent = Agent::where('user_id', Auth::user()->id )->first();
        }
        if($agent == null){
            return 'Bad Request';
        }

        $user = User::where('id', $agent->user_id)->first();
        
        $roles = DB::table('roles')->where('name', 'Agent')
            ->select('id', 'name', 'commission_rate')->get();
        $userRole = $user->roles->pluck('name','name')->all();
        // $userRole = $user->roles()->name;
        
        // dd($userRole);
        return view('agent.edit')->with(compact('agent', 'roles', 'userRole'));
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
            'roles' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required'
        ]);

        if($validator->fails()){
            return redirect()->route('agents.edit', $id)
                            ->withErrors($validator)
                            ->withInput($request->except('roles'));
        }

        // $input = $request->all();
        // if(!empty($input['password'])){ 
        //     $input['password'] = Hash::make($input['password']);
        // }else{
        //     $input = Arr::except($input,array('password'));    
        // }
    
        $agent = Agent::find($id);
         
        $agent->first_name = $request->input('first_name');
        $agent->last_name = $request->input('last_name');
        $agent->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $agent->address = $request->input('address');
        $agent->phone = $request->input('phone');
        $agent->join_date =$request->input('join_date');
        $agent->resign_date = $request->input('resign_date');
        $agent->commission_rate = $request->input('commission_rate');
        $agent->remark = $request->input('remark');
        $agent->updated_by = Auth::user()->name;
        $agent->updated_at = Carbon::now();

        $agent->save();
        $userId = $agent->user_id;
        
        // Just for UAT Duration
        $oldSeller = Seller::where('user_id', $userId)->first();
        if($oldSeller == null){

            $seller = new Seller();

            $seller->sales_id = $agent->sales_id;
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
        return redirect()->route('agents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Agent::find($id)->delete();        
        return response()->json('Delete record successfully!', 200);
    }

    public function getCommissionRate(Request $request){
        $roleName = $request->input('roleName');
        $role = DB::table('roles')
        ->where('name', $roleName)
        ->select('commission_rate')->first();

        return response()->json($role, 200);
    }
}
