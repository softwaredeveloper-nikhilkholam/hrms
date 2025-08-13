<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SystemAsset;
use App\MobileAsset;
use App\OtherAsset;
use App\TempEmpDet;
use Auth;

class AssetsController extends Controller
{    
    public function index()
    {
        $lapAssets = SystemAsset::whereActive(1)->whereType(1)->get();
        $deskAssets = SystemAsset::whereActive(1)->whereType(2)->get();
        $mobAssets = MobileAsset::whereActive(1)->get();
        $simAssets = OtherAsset::whereActive(1)->where('assetType', 1)->get();
        $pendAssets = OtherAsset::whereActive(1)->where('assetType', 2)->get();
        $hardAssets = OtherAsset::whereActive(1)->where('assetType', 3)->get();
        return view('admin.masters.assets.list')->with(['lapAssets'=>$lapAssets,
        'deskAssets'=>$deskAssets,'mobAssets'=>$mobAssets,
        'simAssets'=>$simAssets,'pendAssets'=>$pendAssets,'hardAssets'=>$hardAssets]);
    }

    public function dlist()
    {
        $lapAssets = SystemAsset::whereActive(0)->whereType(1)->get();
        $deskAssets = SystemAsset::whereActive(0)->whereType(2)->get();
        $mobAssets = MobileAsset::whereActive(0)->get();
        $simAssets = OtherAsset::whereActive(0)->where('assetType', 1)->get();
        $pendAssets = OtherAsset::whereActive(0)->where('assetType', 2)->get();
        $hardAssets = OtherAsset::whereActive(0)->where('assetType', 3)->get();
        return view('admin.masters.assets.dlist')->with(['lapAssets'=>$lapAssets,
        'deskAssets'=>$deskAssets,'mobAssets'=>$mobAssets,
        'simAssets'=>$simAssets,'pendAssets'=>$pendAssets,'hardAssets'=>$hardAssets]);
    }
   
    public function create()
    {
        return view('admin.masters.assets.create');        
    }
  
    public function store(Request $request)
    {
        if($request->assetCategory == 1) // Laptop
        { 
            if(SystemAsset::where('MACId', $request->MACId1)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $systemAsset = new SystemAsset();  
            $systemAsset->MACId = $request->MACId1;     
            $systemAsset->motherboard = $request->motherboard1;     
            $systemAsset->serialNo = $request->serialNo1;     
            $systemAsset->make = $request->make1;     
            
            $systemAsset->lapCharger = $request->lapCharger1;     
            $systemAsset->username = $request->username1;     
            $systemAsset->password = $request->password1;     
            $systemAsset->remarks = $request->remarks1;     
            $systemAsset->type = 1;   //Laptop  
            $systemAsset->updated_by=Auth::user()->username;
            $systemAsset->save();
        }
        elseif($request->assetCategory == 5) // Desktop
        { 
            if(SystemAsset::where('MACId', $request->MACId5)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $systemAsset = new SystemAsset();  
            $systemAsset->MACId = $request->MACId5;     
            $systemAsset->motherboard = $request->motherboard5;     
            $systemAsset->serialNo = $request->serialNo5;     
            $systemAsset->username = $request->username5;     
            $systemAsset->password = $request->password5;     
            $systemAsset->remarks = $request->remarks5;     
            $systemAsset->type = 2;  //Desktop   
            $systemAsset->updated_by=Auth::user()->username;
            $systemAsset->save();
        }
        elseif($request->assetCategory == 2) // Mobile
        {
            if(MobileAsset::where('modelNumber', $request->modelNumber2)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $asset = new MobileAsset();  
            $asset->companyName = $request->companyName2;     
            $asset->modelNumber = $request->modelNumber2;     
            $asset->IMEI1 = $request->IMEI12;     
            $asset->IMEI2 = $request->IMEI22;     
            $asset->mobileCharger = $request->mobileCharger2;     
            $asset->OSType = $request->OSType2;     
            $asset->remarks = $request->remarks5;     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->assetCategory == 3) // Sim card
        {
            if(OtherAsset::where('mobNumber', '!=', null)->where('mobNumber', $request->mobNumber3)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $asset = new OtherAsset();  
            $asset->operatComName = $request->operatComName3;     
            $asset->mobNumber = $request->mobNumber3;     
            $asset->extraMat = $request->extraMat3;     
            $asset->assetType = 1; // SIM card     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->assetCategory == 4) // Pendrive
        {
            $asset = new OtherAsset();  
            $asset->operatComName = $request->operatComName4;     
            $asset->storeageSize = $request->storeageSize4;     
            $asset->modelType = $request->modelType4;     
            $asset->extraMat = $request->extraMat4;     
            $asset->assetType = 2; //Pendrive     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->assetCategory == 6) // Hard Disk
        {
            $asset = new OtherAsset();  
            $asset->operatComName = $request->operatComName6;     
            $asset->storeageSize = $request->storeageSize6;     
            $asset->modelType = $request->modelType6;     
            $asset->extraMat = $request->extraMat6;     
            $asset->assetType = 3; // Hard Disk     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Invalid Asset Category Selection. Please TRY another one!!!");;
        }
        return redirect('/assets')->with('success', 'New Asset added successfully!!!');
    }
   
    public function showAsset($type, $id)
    {
        if($type == 1 || $type == 5)
            $asset = SystemAsset::find($id);
        elseif($type == 2)
            $asset = MobileAsset::find($id);
        else
            $asset = OtherAsset::find($id);

        return view('admin.masters.assets.show')->with(['asset'=>$asset,'type'=>$type]);    
    }
  
    public function editAsset($type, $id)
    {
        if($type == 1 || $type == 5)
            $asset = SystemAsset::find($id);
        elseif($type == 2)
            $asset = MobileAsset::find($id);
        else
            $asset = OtherAsset::find($id);

        return view('admin.masters.assets.edit')->with(['asset'=>$asset,'type'=>$type]);  
    }
   
    public function update(Request $request, $id)
    {
        if($request->type == 1) // Laptop
        { 
            if(SystemAsset::where('MACId', $request->MACId1)->where('id', '!=',$request->id)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $systemAsset = SystemAsset::find($id);  
            $systemAsset->MACId = $request->MACId1;     
            $systemAsset->motherboard = $request->motherboard1;     
            $systemAsset->serialNo = $request->serialNo1;     
            $systemAsset->make = $request->make1;     
            $systemAsset->lapBag = $request->lapBag1;     
            $systemAsset->keyboard = $request->keyboard1;     
            $systemAsset->mouse = $request->mouse1;     
            $systemAsset->mousePad = $request->mousePad1;     
            $systemAsset->anti = $request->anti1;     
            $systemAsset->lapCharger = $request->lapCharger1;     
            $systemAsset->username = $request->username1;     
            $systemAsset->password = $request->password1;     
            $systemAsset->extraMaterial = $request->extraMaterial1;     
            $systemAsset->remarks = $request->remarks1;     
            $systemAsset->type = 1;   //Laptop  
            $systemAsset->updated_by=Auth::user()->username;
            $systemAsset->save();
        }
        elseif($request->type == 5) // Desktop
        { 
            if(SystemAsset::where('MACId', $request->MACId5)->where('id', '!=',$request->id)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $systemAsset = SystemAsset::find($id);  
            $systemAsset->MACId = $request->MACId5;     
            $systemAsset->motherboard = $request->motherboard5;     
            $systemAsset->serialNo = $request->serialNo5;     
            $systemAsset->keyboard = $request->keyboard5;     
            $systemAsset->mouse = $request->mouse5;     
            $systemAsset->mousePad = $request->mousePad5;     
            $systemAsset->anti = $request->anti5;     
            $systemAsset->ups = $request->ups5;     
            $systemAsset->username = $request->username5;     
            $systemAsset->password = $request->password5;     
            $systemAsset->extraMaterial = $request->extraMaterial5;     
            $systemAsset->remarks = $request->remarks5;     
            $systemAsset->type = 2;  //Desktop   
            $systemAsset->updated_by=Auth::user()->username;
            $systemAsset->save();
        }
        elseif($request->type == 2) // Mobile
        {
            if(MobileAsset::where('modelNumber', $request->modelNumber2)->where('id', '!=',$request->id)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $asset = MobileAsset::find($id);  
            $asset->companyName = $request->companyName2;     
            $asset->modelNumber = $request->modelNumber2;     
            $asset->IMEI1 = $request->IMEI12;     
            $asset->IMEI2 = $request->IMEI22;     
            $asset->headphone = $request->headphone2;     
            $asset->mobileCover = $request->mobileCover2;     
            $asset->mobileCharger = $request->mobileCharger2;     
            $asset->OSType = $request->OSType2;     
            $asset->extraMat = $request->extraMat2;     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->type == 3) // Sim card
        {
            if(OtherAsset::where('mobNumber', '!=', null)->where('mobNumber', $request->mobNumber3)->count())
                return redirect()->back()->withInput()->with("error","Asset already exist. Please TRY another one!!!");;

            $asset = OtherAsset::find($id);    
            $asset->operatComName = $request->operatComName3;     
            $asset->mobNumber = $request->mobNumber3;     
            $asset->extraMat = $request->extraMat3;     
            $asset->assetType = 1; // SIM card     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->type == 4) // Pendrive
        {
            $asset = OtherAsset::find($id);   
            $asset->operatComName = $request->operatComName4;     
            $asset->storeageSize = $request->storeageSize4;     
            $asset->modelType = $request->modelType4;     
            $asset->extraMat = $request->extraMat4;     
            $asset->assetType = 2; //Pendrive     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        elseif($request->type == 6) // Hard Disk
        {
            $asset = OtherAsset::find($id);    
            $asset->operatComName = $request->operatComName6;     
            $asset->storeageSize = $request->storeageSize6;     
            $asset->modelType = $request->modelType6;     
            $asset->extraMat = $request->extraMat6;     
            $asset->assetType = 3; // Hard Disk     
            $asset->updated_by=Auth::user()->username;
            $asset->save();
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Invalid Asset Category Selection. Please TRY another one!!!");;
        }

        return redirect('/assets')->with('success', 'Asset Updated successfully!!!');
    }  

    public function activate($type, $id)
    {
        if($type == 1 || $type == 5)
            $asset = SystemAsset::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        elseif($type == 2)
            $asset = MobileAsset::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        else
            $asset = OtherAsset::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);

        return redirect('/assets')->with('success', 'Asset Activated successfully!!!');
    }

    public function deactivate($type, $id)
    {
        if($type == 1 || $type == 5)
            $asset = SystemAsset::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        elseif($type == 2)
            $asset = MobileAsset::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        else
            $asset = OtherAsset::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
            
        return redirect('/assets/dlist')->with('success', 'Asset Deactivated successfully!!!');
    }

    public function getAssetDetails($id, $type)
    {
        if($type==1 || $type==5)
            return SystemAsset::find($id);
        elseif($type==2)
            return MobileAsset::find($id);
        elseif($type==3 || $type==4 || $type==6)
            return OtherAsset::find($id);
        else
            return 0;

    }

    public function searchAsset(Request $request)
    {
        return 'asdfads';
        return $request->all();
    }
}
