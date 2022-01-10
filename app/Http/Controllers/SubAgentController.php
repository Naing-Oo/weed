<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

class SubAgentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:sub_agent-list|sub_agent-create|sub_agent-edit|sub_agent-delete', ['only' => ['index','store']]);
        $this->middleware('permission:sub_agent-create', ['only' => ['create','store']]);
        $this->middleware('permission:sub_agent-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:sub_agent-delete', ['only' => ['destroy']]);
        
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
            $subAgents = SubAgent::select('*')
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
                
                $subAgents = SubAgent::where('agent_id', $agentId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

            }elseif ($roleName->name == 'Sub Agent') {

                $subAgents = SubAgent::where('user_id', Auth::user()->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                
            }
            
        }

        return view('sub-agent.index')->with(compact('subAgents'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isAdmin = Auth::user()->is_admin;

        
            $roles = DB::table('roles')->where('name', 'Sub Agent')
                    ->select('id', 'name', 'commission_rate')->get();
            $agents = Agent::select('id', 'user_id' , 'full_name')->get();
        
        return view('sub-agent.create', compact('roles', 'agents'));
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
            'agent_id' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required',
            'agent_id.required' => 'Agent is required'
        ]);
        
        if($validator->fails()){
            return redirect()->route('subAgents.create')
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

        $subAgent = new SubAgent();
        // $agentId = Helper::IDGenerator($agent, 'agent_id', 5, 'AGT'); /** Generate id */
        $uniqueId = uniqid();
         
        $subAgent->agent_id =  $request->input('agent_id');
        $subAgent->user_id = $userId;
        $subAgent->sales_id = $uniqueId;
        $subAgent->first_name = $request->input('first_name');
        $subAgent->last_name = $request->input('last_name');
        $subAgent->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $subAgent->address = $request->input('address');
        $subAgent->email = $request->input('email');
        $subAgent->phone = $request->input('phone');
        $subAgent->join_date =$request->input('join_date');
        $subAgent->resign_date = $request->input('resign_date');
        $subAgent->commission_rate = $request->input('commission_rate');
        $subAgent->remark = $request->input('remark');
        $subAgent->created_by = Auth::user()->name;
        $subAgent->updated_by = Auth::user()->name;
        $subAgent->created_at = Carbon::now();
        $subAgent->updated_at = Carbon::now();
        $subAgent->save();

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
        return redirect()->route('subAgents.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subAgent = SubAgent::find($id);

        return view('sub-agent.details')->with(compact('subAgent'));
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
            $subAgent = SubAgent::find($id);
        }else{
            $subAgent = SubAgent::where('user_id', Auth::user()->id )->first();
        }
        if($subAgent == null){
            return 'Bad Request';
        }
        
        $user = User::where('id', $subAgent->user_id)->first();
        
        $roles = DB::table('roles')->where('name', 'Sub Agent')
            ->select('id', 'name', 'commission_rate')->get();
        $userRole = $user->roles->pluck('name','name')->all();
        $agents = Agent::select('id', 'full_name')->get();
        
        return view('sub-agent.edit')->with(compact('subAgent', 'roles', 'userRole', 'agents'));
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
            'agent_id' => 'required'
        ],[
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'commission_rate.required' => 'Please enter commission rate',
            'roles.required' => 'Role is required',
            'agent_id.required' => 'Agent is required'
        ]);

        if($validator->fails()){
            return redirect()->route('subAgents.edit', $id)
                            ->withErrors($validator)
                            ->withInput($request->except('roles'));
        }

        // $input = $request->all();
        // if(!empty($input['password'])){ 
        //     $input['password'] = Hash::make($input['password']);
        // }else{
        //     $input = Arr::except($input,array('password'));    
        // }
    
        $subAgent = SubAgent::find($id);
         
        $subAgent->agent_id = $request->input('agent_id');
        $subAgent->first_name = $request->input('first_name');
        $subAgent->last_name = $request->input('last_name');
        $subAgent->full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $subAgent->address = $request->input('address');
        $subAgent->phone = $request->input('phone');
        $subAgent->join_date =$request->input('join_date');
        $subAgent->resign_date = $request->input('resign_date');
        $subAgent->commission_rate = $request->input('commission_rate');
        $subAgent->remark = $request->input('remark');
        $subAgent->updated_by = Auth::user()->name;
        $subAgent->updated_at = Carbon::now();

        $subAgent->save();
        $userId = $subAgent->user_id;

        // Just for UAT Duration
        $oldSeller = Seller::where('user_id', $userId)->first();
        if($oldSeller == null){

            $seller = new Seller();

            $seller->sales_id = $subAgent->sales_id;;
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
        return redirect()->route('subAgents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubAgent::find($id)->delete();        
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
