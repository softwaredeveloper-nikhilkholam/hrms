<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\VendorExport;
use App\StoreVendor;
use App\StoreVendorCategory;
use Auth;
use DB;
use Image;
use Excel;

class VendorsController extends Controller
{
    public function index(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            $myInputVendorName = $request->myInputVendorName;
            $myInputCategory = $request->myInputCategory;
            $materialProvoides = $request->materialProvoides;

            $vendors = StoreVendor::whereActive(1);
            if($myInputVendorName != '')
                $vendors = $vendors->where('name', $request->myInputVendorName);

            if($myInputCategory != '')
                $vendors = $vendors->where('category', $request->myInputCategory);

            if($materialProvoides != '')
                $vendors = $vendors->where('materialProvider', 'like', '%' . $materialProvoides . '%');

            $vendors = $vendors->paginate(15);

            $dVendors = StoreVendor::whereActive(0)->count();
            $categories = StoreVendor::distinct('category')->where('category', '!=', '')->orderBy('category')->get(['category']);
            return view('storeAdmin.masters.vendors.list')->with(['materialProvoides'=>$materialProvoides,'categories'=>$categories,
            'myInputCategory'=>$myInputCategory,'myInputVendorName'=>$myInputVendorName,'vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
    }

    public function list()
    {
        $userType = Auth::user()->userType;
       if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            $vendors = StoreVendor::whereActive(1)->paginate(1);
            $dVendors = StoreVendor::whereActive(0)->count();
            return view('storeAdmin.masters.vendors.list')->with(['vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
    }

    public function dlist(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            $myInputVendorName = $request->myInputVendorName;
            $myInputCategory = $request->myInputCategory;
            $materialProvoides = $request->materialProvoides;

            $dVendors = StoreVendor::whereActive(0);
            if($myInputVendorName != '')
                $dVendors = $dVendors->where('name', $request->myInputVendorName);

            if($myInputCategory != '')
                $dVendors = $dVendors->where('category', $request->myInputCategory);

            if($materialProvoides != '')
                $dVendors = $dVendors->where('materialProvider', 'like', '%' . $materialProvoides . '%');

            $dVendors = $dVendors->paginate(15);

            $vendors = StoreVendor::whereActive(0)->count();
            $categories = StoreVendor::distinct('category')->where('category', '!=', '')->orderBy('category')->get(['category']);
            return view('storeAdmin.masters.vendors.dlist')->with(['materialProvoides'=>$materialProvoides,'categories'=>$categories,
            'myInputCategory'=>$myInputCategory,'myInputVendorName'=>$myInputVendorName,'vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
       
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            $vendors = StoreVendor::whereActive(1)->count();
            $dVendors = StoreVendor::whereActive(0)->count();
            $categories = StoreVendorCategory::orderBy('name')->pluck('name','id');
            return view('storeAdmin.masters.vendors.create')->with(['categories'=>$categories,'vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
       
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            if(StoreVendor::where('name', $request->name)->count())
            {
                return redirect()->back()->withInput()->with("error","Vendor Name is already exist..");
            }

            $vendor = new StoreVendor;
            
            if($request->categoryId == 14)
            {
                $categoryExist = StoreVendorCategory::where('name', $request->otherCategory)->first();
                if(!$categoryExist)
                {
                    $otherCategory = new StoreVendorCategory;
                    $otherCategory->name =$request->otherCategory;
                    $otherCategory->updated_by=Auth::user()->username;
                    $otherCategory->save();  
                }
                else
                {
                    $vendor->categoryId = $categoryExist->id;
                }

                $vendor->category = $request->otherCategory;
                
            }
            else
            {
                $vendor->category = StoreVendorCategory::where('id', $request->categoryId)->value('name');
                $vendor->categoryId = $request->categoryId;
            }

            $vendor->addedBy = strtoupper(Auth::user()->name);
            $vendor->name = strtoupper($request->name);
            $vendor->address = $request->address;
            $vendor->PANNO = $request->PANNO;
            $vendor->whatsappNo = $request->whatsappNo;
            $vendor->landlineNo = $request->landlineNo;
            $vendor->contactPerson1 = $request->contactPerson1;
            $vendor->contactPerNo1 = $request->contactPerNo1;
            $vendor->contactPerson2 = $request->contactPerson2;
            $vendor->contactPerNo2 = $request->contactPerNo2;
            $vendor->emailId = $request->emailId;
            $vendor->materialProvider = $request->materialProvider;
            $vendor->accountNo = $request->accountNo;
            $vendor->IFSCCode = $request->IFSCCode;
            $vendor->bankBranch = $request->bankBranch;
            $vendor->GSTNo = $request->GSTNo;
            $vendor->outstandingRs = $request->outstandingRs;
            $vendor->rating = $request->rating;
            if(!empty($request->file('image')))
            {
                $originalImage= $request->file('image');
                $Image = $request->productCode.'.'.$originalImage->getClientOriginalExtension();
                $image = Image::make($originalImage);
                $originalPath =  public_path()."/storeAdmin/images/vendors/";
                $image->resize(500,500);
                $image->save($originalPath.$Image);
                $vendor->image = $Image;
            }

            $vendor->updated_by=Auth::user()->username;
            $vendor->save();
            return redirect('/vendor')->with("success","Vendor Store successfully..");
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        
    }

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801' || $userType == '61' || $userType == '801')
        {
            $vendor = StoreVendor::find($id);
            $vendors = StoreVendor::whereActive(1)->count();
            $dVendors = StoreVendor::whereActive(0)->count();
            return view('storeAdmin.masters.vendors.show')->with(['vendor'=>$vendor,'vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
       
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");

    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            $vendor = StoreVendor::find($id);
            $categories = StoreVendorCategory::orderBy('name')->pluck('name','id');
            $vendors = StoreVendor::whereActive(1)->count();
            $dVendors = StoreVendor::whereActive(0)->count();
            return view('storeAdmin.masters.vendors.edit')->with(['categories'=>$categories,'vendor'=>$vendor,'vendors'=>$vendors,'dVendors'=>$dVendors]);
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
       
    }

    public function update(Request $request, $id)
    {
    
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801' || $userType == '61')
        {
            if(StoreVendor::where('name', $request->name)->where('id', '<>', $id)->count())
            {
                return redirect()->back()->withInput()->with("error","Vendor Name is already exist..");
            }

           $vendor = StoreVendor::find($id);
           

            if($request->categoryId == 14)
            {
                $categoryExist = StoreVendorCategory::where('name', $request->otherCategory)->count();
                if(!$categoryExist)
                {
                    $otherCategory = new StoreVendorCategory;
                    $otherCategory->name =$request->otherCategory;
                    $otherCategory->updated_by=Auth::user()->username;
                    $otherCategory->save();  
                    
                }

                $vendor->category = $request->otherCategory;
                $vendor->categoryId = $otherCategory->id;
            }
            else
            {
                $vendor->category = StoreVendorCategory::where('id', $request->categoryId)->value('name');
                $vendor->categoryId = $request->categoryId;
            }

            $vendor->name = strtoupper($request->name);
            $vendor->address = $request->address;
            $vendor->PANNO = $request->PANNO;
            $vendor->whatsappNo = $request->whatsappNo;
            $vendor->landlineNo = $request->landlineNo;
            $vendor->contactPerson1 = $request->contactPerson1;
            $vendor->contactPerNo1 = $request->contactPerNo1;
            $vendor->contactPerson2 = $request->contactPerson2;
            $vendor->contactPerNo2 = $request->contactPerNo2;
            $vendor->typeOfCompany = $request->typeOfCompany;
            $vendor->emailId = $request->emailId;
            $vendor->materialProvider = $request->materialProvider;
            $vendor->accountNo = $request->accountNo;
            $vendor->IFSCCode = $request->IFSCCode;
            $vendor->bankBranch = $request->bankBranch;
            $vendor->GSTNo = $request->GSTNo;
            $vendor->outstandingRs = $request->outstandingRs;
            $vendor->rating = $request->rating;
            if(!empty($request->file('image')))
            {
                if($vendor->image != '')
                {
                    $oldImage = base_path('public/storeAdmin/images/vendors/').$vendor->image;

                    if (File::exists($oldImage))  // unlink or remove previous image from folder
                    {
                        unlink($oldImage);
                    }
                }

                $originalImage= $request->file('image');
                $Image = $request->productCode.'.'.$originalImage->getClientOriginalExtension();
                $image = Image::make($originalImage);
                $originalPath =  public_path()."/storeAdmin/images/vendors/";
                $image->save($originalPath.$Image);
                $vendor->image = $Image;
            }

            $vendor->updated_by=Auth::user()->username;
            $vendor->save();
            return redirect('/vendor')->with("success","Vendor Updated successfully..");
        }
        return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
    }


    public function deactivate($id)
    {
        $vendor = StoreVendor::find($id);
        if($vendor)
        {
            $vendor->active=0;
            $vendor->updated_by=Auth::user()->username;
            $vendor->save();
            return redirect('/vendor/dlist')->with("success","Vendor Deactivated successfully..");
        }
        else
        {
            return redirect('/vendor')->with("success","Something went wrong...");
        }
    }

    public function activate($id)
    {
        $vendor = StoreVendor::find($id);
        if($vendor)
        {
            $vendor->active=1;
            $vendor->updated_by=Auth::user()->username;
            $vendor->save();
            return redirect('/vendor')->with("success","Vendor Activated successfully..");
        }
        else
        {
            return redirect('/vendor')->with("success","Something went wrong...");
        }
    }

    public function exportExcel($status)
    {
        $fileName = 'VendorList.xlsx';
        return Excel::download(new VendorExport($status), $fileName);
    }

}
