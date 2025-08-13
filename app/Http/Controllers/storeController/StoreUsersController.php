<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreUser;
use App\StoreCategory;
use App\Department;
use App\Designation;
use App\ContactusLandPage;
use App\EmpDet;
use App\User;
use App\TypeOfCompany;
use Auth;
use Hash;
use DB;

class StoreUsersController extends Controller
{
    public function index()
    {
        $users1 = User::join('departments', 'users.reqDepartmentId', 'departments.id')
        ->join('contactus_land_pages', 'users.reqBranchId', 'contactus_land_pages.id')
        ->join('designations', 'users.designationId', 'designations.id')
        ->select('users.*', 'departments.name as departmentName', 'contactus_land_pages.branchName', 'designations.name as designationName')
        ->where('userType', 801)
        ->where('users.active', 1)
        ->get();

        $users2 = User::where('users.active', 1)
        ->where('users.storeAccess', 1)
        ->get();

        $users1 = collect($users1);
        $users2 = collect($users2);
        $users = $users1->merge($users2);

        $dUsers = User::where('userType', '801')->whereActive(0)->count();

        return view('storeAdmin.masters.users.list')->with(['users'=>$users,'dUsers'=>$dUsers]);
    }

    public function dlist()
    {
        $users1 = User::join('departments', 'users.reqDepartmentId', 'departments.id')
        ->join('contactus_land_pages', 'users.reqBranchId', 'contactus_land_pages.id')
        ->join('designations', 'users.designationId', 'designations.id')
        ->select('users.*', 'departments.name as departmentName', 'contactus_land_pages.branchName', 'designations.name as designationName')
        ->where('userType', 801)
        ->where('users.active', 0)
        ->get();

        $users2 = User::where('users.active', 0)
        ->where('users.storeAccess', 1)
        ->get();

        $users1 = collect($users1);
        $users2 = collect($users2);
        $users = $users1->merge($users2);

        $aciveUsers = User::where('userType', '801')->whereActive(1)->count();
        return view('storeAdmin.masters.users.dlist')->with(['users'=>$users,'aciveUsers'=>$aciveUsers]);
    }

    public function create(Request $request)
    {
        $users = User::where('userType', '801')->whereActive(1)->count();
        $dUsers = User::where('userType', '801')->whereActive(0)->count();
        $categories = StoreCategory::select('name', 'id')->orderBy('name')->whereActive(1)->get();
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $typeOfCompanies = TypeOfCompany::whereStatus(1)->pluck('name', 'id');
        return view('storeAdmin.masters.users.create')->with(['typeOfCompanies'=>$typeOfCompanies,'categories'=>$categories,'branches'=>$branches,'departments'=>$departments,'designations'=>$designations,
        'users'=>$users,'dUsers'=>$dUsers]);
    }

    public function store(Request $request)
    {
        if(User::where('username', $request->username)->count())
        {
            return redirect()->back()->withInput()->with("error","Username is already exist..");
        }

        if($request->empCode != 00000)
        {

            $employee = EmpDet::where('empCode', $request->empCode)->first();
            $user = new User();
            $user->name = $employee->name;
            $user->username = $request->username;
            $user->designationId= $employee->designationId;
            $user->reqDepartmentId = $request->departmentId;
            $user->reqBranchId = $request->branchId;
            $user->typeOfCompany = implode(",",$request->typeOfCompany);
            $user->email = $employee->email;
            $user->password = Hash::make($request->password);
            $user->viewPassword = $request->password;  
            if(isset($request->forQuotation))      
                $user->purchaseAccess = 1;  
            else
                $user->purchaseAccess = 0;    
            
            if(isset($request->forBranchAdmin))      
                $user->subStoreAccess = 1;  
            else
                $user->subStoreAccess = 0;    

            if(isset($request->forAsset))      
                $user->assetAccess = 1;  
            else
                $user->assetAccess = 0;    


            $user->empId = $employee->id;  
            $user->userRoleId =  19;
            $user->userType = '801';
            $count = count($request->categoryId);
            if($count == 0)
            {
                return redirect()->back()->withInput()->with("error","Please select at least 1 category...");
            }
        }
        else
        {
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->designationId= $request->designationId;
            $user->reqDepartmentId = $request->departmentId;
            $user->reqBranchId = $request->branchId;
            $user->typeOfCompany = implode(", ",$request->typeOfCompany);
            $user->password = Hash::make($request->password);
            $user->viewPassword = $request->password;  
            if(isset($request->forQuotation))      
                $user->purchaseAccess = 1;  
            else
                $user->purchaseAccess = 0; 


            if(isset($request->forAsset))      
                $user->assetAccess = 1;  
            else
                $user->assetAccess = 0;    
            
            if(isset($request->forBranchAdmin))     
            { 
                $user->subStoreAccess = 1;  
                $user->userType = '1002';
            }
            else
            {
                $user->subStoreAccess = 0; 
                $user->userType = '801';
            }   

            $user->userRoleId =  19;
            
            $count = count($request->categoryId);
            if($count == 0)
            {
                return redirect()->back()->withInput()->with("error","Please select at least 1 category...");
            }
        }

        $user->updated_by = Auth::user()->username;
        if($user->save())
        {
            $count = count($request->categoryId);
            for($i=0; $i<$count; $i++)
            {
                $storeUser = new StoreUser;
                $storeUser->userId=$user->id;
                $storeUser->categoryId=$request->categoryId[$i];
                $storeUser->userType='801';
                $storeUser->updated_by=Auth::user()->username;
                $storeUser->save();
            }
        }

        return redirect('/storeUser')->with("success","Store User Created Successfully..");
    }

    public function edit($id)
    {
        $user = User::find($id);
        $employee = EmpDet::find($user->empId);
        $users = User::where('userType', '801')->whereActive(1)->count();
        $dUser = User::where('userType', '801')->whereActive(0)->count();
        $categories = StoreCategory::select('name', 'id')->orderBy('name')->whereActive(1)->get();
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
        foreach($categories as $temp)
        {
            $userCategory = StoreUser::where('active', 1)->where('userId', $user->id)->where('categoryId', $temp->id)->count();
            if($userCategory)
                $temp['selected']=1;
            else
                $temp['selected']=0;

        }
        return view('storeAdmin.masters.users.edit')->with(['categories'=>$categories,'branches'=>$branches,
        'departments'=>$departments,'designations'=>$designations,'user'=>$user,'users'=>$users,'dUser'=>$dUser]);
    }

    public function update(Request $request, $id)
    {
        if(User::where('username', $request->username)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Username is already exist..");
        }

        $employee = EmpDet::where('empCode', $request->empCode)->first();
        $user = User::find($id);
        $user->username = $request->username;
        $user->reqDepartmentId = $request->departmentId;
        $user->reqBranchId = $request->branchId;
        // return $request->typeOfCompany;
        $user->typeOfCompany = implode(",",$request->typeOfCompany);
        $user->password = Hash::make($request->password);
        $user->viewPassword = $request->password;  
        if(isset($request->forQuotation))      
            $user->purchaseAccess = 1;  
        else
            $user->purchaseAccess = 0;   
        
        if(isset($request->forAsset))      
            $user->assetAccess = 1;  
        else
            $user->assetAccess = 0;    

        $user->userRoleId =  19;
        $user->updated_by = Auth::user()->username;
        if($user->save())
        {
            StoreUser::where('userId', $user->id)->where('userType', 801)->update(['active'=>0]);
            $count = count($request->categoryId);
            for($i=0; $i<$count; $i++)
            {
                $storeUser = StoreUser::where('userId', $user->id)->where('categoryId', $request->categoryId[$i])->first();
                if(!$storeUser)
                    $storeUser = new StoreUser;

                $storeUser->userId=$user->id;
                $storeUser->categoryId=$request->categoryId[$i];
                $storeUser->userType='801';
                $storeUser->active=1;
                $storeUser->updated_by=Auth::user()->username;
                $storeUser->save();
            }
            
        }

        return redirect('/storeUser')->with("success","Store User Updated successfully..");
    }

    public function activate($id)
    {
        $user = User::find($id);
        $user->active = 1;
        $user->updated_by=Auth::user()->username;
        $user->save();
        return redirect('/storeUser')->with("success","User Activate Successfully.");
    }

    public function deactivate($id)
    {
        $user = User::find($id);
        $user->active = 0;
        $user->updated_by=Auth::user()->username;
        $user->save();
        return redirect('/storeUser/dlist')->with("success","User Deactivated Successfully.");
    }

    public function getEmployee($empCode)
    {
        return EmpDet::select('name', 'empCode','branchId', 'departmentId', 'designationId')->where('empCode', $empCode)->where('active', 1)->first();
    }

    public function checkUsername($username)
    {
       return User::where('username', $username)->count();
    }
}
