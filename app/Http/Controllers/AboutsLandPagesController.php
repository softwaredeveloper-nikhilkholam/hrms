<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutsLandPage;
use Auth;

class AboutsLandPagesController extends Controller
{
    public function index()
    {
        $abouts = AboutsLandPage::paginate(10);
        return view('landingPages.aboutus')->with(['abouts'=>$abouts, 'flag'=>1]);
    }

    public function create()
    {
        $abouts = AboutsLandPage::paginate(10);
        return view('landingPages.aboutus')->with(['abouts'=>$abouts, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        
            $about = new AboutsLandPage();
            $about->description=$request->description;
            $about->subTitle1=$request->subTitle1;
            $about->description1=$request->description1;
            $about->subTitle2=$request->subTitle2;
            $about->description2=$request->description2;
            $about->subTitle3=$request->subTitle3;
            $about->description3=$request->description3;
            $about->rating=$request->rating;
            $about->updated_by=Auth::user()->username;
            if($about->save())
            {
                AboutsLandPage::where('id', '!=', $about->id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
            }

            return redirect('/aboutusLandPage')->with("success","About us Information Store successfully..");
        
    }

    public function edit($id)
    {
        $abouts = AboutsLandPage::paginate(10);
        $about = AboutsLandPage::find($id);
        return view('landingPages.aboutus')->with(['about'=>$about, 'abouts'=>$abouts, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {        
        $about = AboutsLandPage::find($id);
        $about->description=$request->description;
        $about->subTitle1=$request->subTitle1;
        $about->description1=$request->description1;
        $about->subTitle2=$request->subTitle2;
        $about->description2=$request->description2;
        $about->subTitle3=$request->subTitle3;
        $about->description3=$request->description3;
        $about->rating=$request->rating;
        $about->updated_by=Auth::user()->username;
        $about->save();

        return redirect('aboutusLandPage')->with("success","About us Information Updated successfully..");
    }

    public function show($id)
    {
        $abouts = AboutsLandPage::paginate(10);
        $about = AboutsLandPage::find($id);
        return view('landingPages.aboutus')->with(['about'=>$about, 'abouts'=>$abouts, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        AboutsLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        AboutsLandPage::where('id', '!=', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);

        return redirect('aboutusLandPage')->with("success","About us Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        AboutsLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('aboutusLandPage')->with("success","About us Deactivated successfully..");        
    }

} 
