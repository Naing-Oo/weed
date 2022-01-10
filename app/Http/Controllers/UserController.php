<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;
use Carbon\Carbon;

class UserController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('is_admin', true)->select('users.*', 'roles.name as role_name')
                ->leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
                ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
                ->get();
        $roles = DB::table('roles')->pluck('name', 'name')->all();

        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required'
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->is_admin = 1;
        $user->save();
        $userId = $user->id;

        $seller = new Seller();

        $seller->sales_id = 'weed';
        $seller->user_id = $userId;
        $seller->role_name = $request->input('roles')[0];
        $seller->name = $request->input('name');
        $seller->email = $request->input('email');
        $seller->password = Hash::make($request->input('password'));
        $seller->created_at = Carbon::now();
        $seller->updated_at = Carbon::now();
        $seller->save();

        DB::table('model_has_roles')->where('model_id',$userId)->delete();
        $user->assignRole($request->input('roles'));

        session()->flash('message', 'Created Successfully !');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = DB::table('roles')->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        
        return view('user.edit', compact('user', 'roles', 'userRole'));
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
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required'
        ]);

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        $userId = $user->id;

        // $seller = Seller::findOrFail($userId);
        $seller = new Seller();

        $seller->sales_id = 'weed';
        $seller->user_id = $userId;
        $seller->role_name = $request->input('roles')[0];
        $seller->name = $request->input('name');
        $seller->email = $request->input('email');
        $seller->password = Hash::make($request->input('password'));
        $seller->created_at = Carbon::now();
        $seller->updated_at = Carbon::now();
        $seller->save();

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        session()->flash('message', 'Updated Successfully !');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json('Delete record successfully!', 200);
    }
}
