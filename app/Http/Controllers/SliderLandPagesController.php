<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SliderLandPage;
use Auth;
use Image;
use File;

class SliderLandPagesController extends Controller
{
    public function index()
    {
        $sliders = SliderLandPage::where('active', 1)->orderBy('id', 'desc')->paginate(50);
        return view('landingPages.slider')->with(['sliders'=>$sliders, 'flag'=>1]);
    }

    public function create()
    {
        $sliders = SliderLandPage::orderBy('id', 'desc')->paginate(50);
        return view('landingPages.slider')->with(['sliders'=>$sliders, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        if(SliderLandPage::where('title', $request->title)->count())
        {
            return redirect()->back()->withInput()->with("error","Slider Title is already exist..");
        }

        $slider = new SliderLandPage();
        $slider->title=$request->title;
        $slider->description=$request->description;
        $slider->titleDescAlign=$request->titleDescAlign;
        //Image insert.
        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = date('ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/landingpage/sliders/";
            $image->resize(1600,600);
            $image->save($originalPath.$Image);
            $slider->image = $Image;
        }

        $slider->updated_by=Auth::user()->username;
        if($slider->save())
        {
            SliderLandPage::where('id', '!=', $slider->id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        }
        return redirect('/sliderLandPage')->with("success","Slider Information Store successfully..");
    }

    public function edit($id)
    {
        $sliders = SliderLandPage::where('active', 1)->orderBy('id', 'desc')->paginate(50);
        $slider = SliderLandPage::find($id);
        return view('landingPages.slider')->with(['slider'=>$slider, 'sliders'=>$sliders, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $slider = SliderLandPage::find($id);
        $slider->title=$request->title;
        $slider->description=$request->description;
        if(!empty($request->file('image')))
        {
            $oldImage = base_path('public/landingpage/sliders/').$slider->image;
            if($slider->image != '')
            {
                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }
            
            $originalImage= $request->file('image');
            $Image = date('ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/landingpage/sliders/";
            $image->resize(1600,600);
            $image->save($originalPath.$Image);
            $slider->image = $Image;
        }
        $slider->titleDescAlign=$request->titleDescAlign;
        $slider->updated_by=Auth::user()->username;
        $slider->save();

        return redirect('sliderLandPage')->with("success","Slider Information Updated successfully..");
    }

    public function show($id)
    {
        $sliders = SliderLandPage::orderBy('id', 'desc')->paginate(50);
        $slider = SliderLandPage::find($id);
        return view('landingPages.slider')->with(['slider'=>$slider, 'sliders'=>$sliders, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        SliderLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        SliderLandPage::where('id', '!=', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);

        return redirect('sliderLandPage')->with("success","Slider Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        SliderLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('sliderLandPage')->with("success","Slider Deactivated successfully..");        
    }

}
