<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AppMenu;
use App\UserMenu;
use App\Department;
use App\User;
use App\EmpDet;
use App\UserRole;
use Auth;
use DB;
use Hash;

class UserAllocationsController extends Controller
{
    public function index(Request $request)
    {
        $users = User::join('departments', 'users.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'users.*')
        ->whereIn('users.userRoleId', [6,7,8,9,10,18,22])
        ->get();
        return view('admin.userAllocations.list', compact('users'));
    }

    public function changeLogin($type)
    {
        $user = Auth::user();
        $id = $user->id;
        $user->loginFlag = $type;
        $user->save();
        if($user->menus == '')
            return redirect('/dashboard')->with("error","Menus not assigned, so you can use only personal login");

        return redirect('/dashboard')->with("success","Login Changed Successfully..");
    }

    public function updateMenu(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType;

        if($userType == '61')
            $assingMenu = implode(", ",$request->AD);

        if($userType == '51')
            $assingMenu = implode(", ",$request->HR);
            
        $userId = $request->userId;
        $menuUser = User::find($userId);
        if($user)
        {
            $menuUser->menus = $assingMenu;
            $menuUser->deptUserType = $userType;
            $menuUser->save();
        }
        else
            return redirect()->back()->withInput()->with("error","Invalid User");

        return redirect('/home')->with("success","Menu assign to User successfully..");
    }

    public function create(Request $request)

    {
        $empCode = $request->empCode;
        $empDet=[];
        if($empCode != '')
        {
            $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.id','emp_dets.empCode','emp_dets.name','emp_dets.active', 
            'contactus_land_pages.branchName','departments.name as departmentName','designations.name as designationName')
            ->where('emp_dets.empCode', $empCode)
            ->first();
        }

        $userRoles = UserRole::whereIn('id', [6,7,8,9,10,18,22])->where('active', 1)->orderBy('name')->pluck('name','userType');
        return view('admin.userAllocations.create', compact('users','empCode','empDet','userRoles'));

    }

    public function store(Request $request)
    {
        if(User::where('username', $request->username)->count())
            return redirect()->back()->withInput()->with("error","Username already exist...");
            
        DB::beginTransaction();

        try 
        {
            $employee = EmpDet::where('id', $request->empId)->first();
            $user = new User();
            $user->departmentId = $employee->departmentId;
            $user->designationId = $employee->designationId;
            $user->name = $employee->name;
            $user->username = $request->username;
            $user->email = $employee->email;
            $user->password = Hash::make($request->password);
            $user->viewPassword = $request->password;
            $user->userEmpId = $employee->id;
            $user->newUser = 1;
            $user->userType =  $request->userRoleId;
            $user->userRoleId = UserRole::where('userType',$request->userRoleId)->value('id');
            $user->updated_by = Auth::user()->username;
            $user->save();
            DB::commit();
            return redirect('/userAllocations')->with("success","New User Created successfuly.");

        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","something went wrong : ".$e->getMessage());
        }
        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}
