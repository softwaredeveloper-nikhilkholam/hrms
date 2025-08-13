<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Hash;
use Mail;
use App\User;
use App\SliderLandPage;
use App\UserRole;
use App\EmpDet;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/home';   

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        $imageName = SliderLandPage::where('active', 1)->value('image');
        return view('auth.login')->with(['imageName'=>$imageName]);
    } 

    public function testlogin()
    {
        $imageName = SliderLandPage::where('active', 1)->value('image');
        return view('auth.testlogin')->with(['imageName'=>$imageName]);
    } 
    

    protected function postLogin(Request $request)
    {
        request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

         $user = User::where('username', $request->username)->first();
     
        if($user)
        {
            if($user->active == 0)
                return redirect('/login')->with("error","You don't have permission. username is deactivated..");
        }

        if(Auth::attempt(['username' => $request->username, 'password' =>$request->password])) 
        {            
          
            $username = $user->username;
            $userType = $user->userType;
            $userRole = UserRole::where('id', $user->userRoleId)->value('name');
            $request->session()->put('password', $request->password);  
            $request->session()->put('username', $username);               
            $request->session()->put('userType', $userType);               
            $request->session()->put('userRole', $userRole);
            if($userType == '701')
            {
                return redirect('/purchaseHome')->with("success","Welcome To Inventory System");
            }

            if($userType == '31' || $userType == '21' || $userType == '11')
            {
                $empDet=EmpDet::where('id', $user->empId)->whereIn('userRoleId', [3,5,11])->first();
                $request->session()->put('departmentId', $empDet->departmentId);               
                $request->session()->put('designationId', $empDet->designationId);       
                $request->session()->put('authorityName', $empDet->name);       
                $request->session()->put('profilePhoto', $empDet->profilePhoto);       
                $request->session()->put('salary', $empDet->salaryScale);       
                $request->session()->put('empCode', $empDet->empCode);       
                $request->session()->put('empCardStatus', $empDet->idCardStatus);       
            }              
 
            if($user->newUser == 0)
            {
                $request->session()->put('passFlag', '0');
                return redirect('/change-password')->with("warning","Welcome To HRMS, Please change Password..");           
            }   
            else 
            {     
                $request->session()->put('passFlag', '1');   
                $request->Session()->put('userRole', $userRole); 
                $request->Session()->put('name', $user->name); 
                return redirect('/home')->with("success","Welcome To HRMS");
            }
        }
        else 
        {
            return redirect('/login')->with("error","Invalid username or password!!!");
        }
    } 
    
    public function logout(Request $request)
    {
        $user = Auth::user();        
        $this->guard()->logout(); 
        $request->session()->flush();
        $request->session()->regenerate();     
    
        return redirect('/login')->with('success', 'Logout successfully');
    }   
    
    public function forgot(Request $request)
    {
        return view('auth.passwords.forgotPassword');
    }
    
    public function forgotPassword(Request $request)
    {
        $username = $request->username;
        $user = User::where('username', $username)->first();
        if($user)
        {
            if($user->email == '')
                return redirect('/login')->with("error","your email id not registered in the HRMS system, please contact HR Team.");
            
            $user->password = Hash::make('welcome');
            $user->save();
            $email=$user->email;
            $name=$user->name;
            $username=$user->username;
            Mail::send('mail', ['username'=>$username,'name'=>$name,'password' => 'welcome','flag'=>'newEmployee'], function ($message) use ($email) {
                $message->to($email)->subject('New Password');
            });   
            
            return redirect('/login')->with("success","New Password is sent on your registered EmailId, Please check your email Id");
        }
        else
        {
            return redirect('/forgot')->with("error","This username not registered in our HRMS.");
        }
    }
}
