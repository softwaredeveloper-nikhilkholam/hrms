<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialMediaLandPage;
use Auth;

class MediaLandPagesController extends Controller
{
    public function index()
    {
        $medias = SocialMediaLandPage::paginate(10);
        return view('landingPages.media')->with(['medias'=>$medias, 'flag'=>1]);
    }

    public function create()
    {
        $medias = SocialMediaLandPage::paginate(10);
        return view('landingPages.media')->with(['medias'=>$medias, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        $media = new SocialMediaLandPage();
        $media->twitter=$request->twitter;
        $media->instagram=$request->instagram;
        $media->whatsapp=$request->whatsapp;
        $media->facebook=$request->facebook;
        $media->gmail=$request->gmail;
        $media->updated_by=Auth::user()->username;
        $media->save();

        return redirect('/socialMediaLandPage')->with("success","Social Media Store successfully..");
    }

    public function edit($id)
    {
        $medias = SocialMediaLandPage::paginate(10);
        $media = SocialMediaLandPage::find($id);
        return view('landingPages.media')->with(['media'=>$media, 'medias'=>$medias, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $media = SocialMediaLandPage::find($id);
        $media->projects=$request->projects;
        $media->happyParents=$request->happyParents;
        $media->awards=$request->awards;
        $media->happyEmployees=$request->happyEmployees;
        $media->updated_by=Auth::user()->username;
        $media->save();
        return redirect('/socialMediaLandPage')->with("success","Social Media updated successfully..");
    }

    public function show($id)
    {
        $medias = SocialMediaLandPage::paginate(10);
        $media = SocialMediaLandPage::find($id);
        return view('landingPages.media')->with(['media'=>$media, 'medias'=>$medias, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        SocialMediaLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('socialMediaLandPage')->with("success","Social Media Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        SocialMediaLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('socialMediaLandPage')->with("success","Social Media Deactivated successfully..");        
    }

}
