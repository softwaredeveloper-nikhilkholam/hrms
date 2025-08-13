<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BusinessLogoLandPage;
use Auth;
use Image;
use File;

class BusinessLogoLandPagesController extends Controller
{
    public function index()
    {
        $logos = BusinessLogoLandPage::paginate(10);
        return view('landingPages.businesslogo')->with(['logos'=>$logos, 'flag'=>1]);
    }

    public function create()
    {
        $logos = BusinessLogoLandPage::paginate(10);
        return view('landingPages.businesslogo')->with(['logos'=>$logos, 'flag'=>1]);
    }

    public function store(Request $request)
    {       
        //Image insert.
        if(!empty($request->file('logo')))
        {
            if($request->hasfile('logo')) { 
                foreach($request->file('logo') as $file)
                {
                    $logo = new BusinessLogoLandPage();

                    $fileName = time().rand(0, 1000);
                    $fileName = $fileName.'.'.$file->getClientOriginalExtension();
                    $file->move(public_path()."/landingpage/businesslogo/",$fileName);

                    $logo->logoImage = $fileName;
                    $logo->updated_by=Auth::user()->username;
                    $logo->save();
                    
                }
            }
        }      

        return redirect('/businesslogoLandPage')->with("success","Slider Information Store successfully..");
    }

    public function edit($id)
    {
        $logos = BusinessLogoLandPage::paginate(10);
        $logo = BusinessLogoLandPage::find($id);
        return view('landingPages.businesslogo')->with(['logo'=>$logo, 'logos'=>$logos, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $logo = BusinessLogoLandPage::find($id);
        if(!empty($request->file('logo')))
        {
            $oldImage = base_path('public/landingpage/businesslogo/').$logo->logoImage;
            if($logo->logoImage != '')
            {
                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }

            $originalImage= $request->file('logo');
            $Image = date('dmyhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/landingpage/businesslogo/";
            $image->resize(632,190);
            $image->save($originalPath.$Image);
            $logo->logoImage = $Image;
        }

        $logo->updated_by=Auth::user()->username;
        $logo->save();

        return redirect('/businesslogoLandPage')->with("success","Business Logo Updated successfully..");
    }

    public function show($id)
    {
        $logos = BusinessLogoLandPage::paginate(10);
        $logo = BusinessLogoLandPage::find($id);
        return view('landingPages.businesslogo')->with(['logo'=>$logo, 'logos'=>$logos, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        BusinessLogoLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/businesslogoLandPage')->with("success","Business Logo Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        BusinessLogoLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/businesslogoLandPage')->with("success","Business Logo Deactivated successfully..");        
    }

}
