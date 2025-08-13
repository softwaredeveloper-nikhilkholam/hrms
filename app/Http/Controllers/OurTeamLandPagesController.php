<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeamLandPage;
use Auth;
use Image;
use File;

class OurTeamLandPagesController extends Controller
{
    public function index()
    {
        $teams = TeamLandPage::paginate(10);
        return view('landingPages.team')->with(['teams'=>$teams, 'flag'=>1]);
    }

    public function create()
    {
        $teams = TeamLandPage::paginate(10);
        return view('landingPages.team')->with(['teams'=>$teams, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        if(TeamLandPage::where('name', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Name is already exist..");
        }

        $team = new TeamLandPage();
        $team->name=$request->name;
        $team->designation=$request->designation;
        $team->description=$request->description;
        //Image insert.
        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = $team->title.'_'.date('his').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/landingpage/teams/";
            $image->save($originalPath.$Image);
            $team->photo = $Image;
        }

        $team->updated_by=Auth::user()->username;
        $team->save();

        return redirect('/ourTeamLandPage')->with("success","Slider Information Store successfully..");
    }

    public function edit($id)
    {
        $teams = TeamLandPage::paginate(10);
        $team = TeamLandPage::find($id);
        return view('landingPages.team')->with(['team'=>$team, 'teams'=>$teams, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $team = TeamLandPage::find($id);
        $team->name=$request->name;
        $team->designation=$request->designation;
        $team->description=$request->description;
        //Image insert.
        if(!empty($request->file('image')))
        {
            $oldImage = base_path('public/landingpage/teams/').$team->photo;
            if($team->photo != '')
            {
                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }

            $originalImage= $request->file('image');
            $Image = $team->title.'_'.date('his').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/landingpage/teams/";
            $image->save($originalPath.$Image);
            $team->photo = $Image;
        }
        $team->updated_by=Auth::user()->username;
        $team->save();

        return redirect('ourTeamLandPage')->with("success","Team Member Information Updated successfully..");
    }

    public function show($id)
    {
        $teams = TeamLandPage::paginate(10);
        $team = TeamLandPage::find($id);
        return view('landingPages.team')->with(['team'=>$team, 'teams'=>$teams, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        TeamLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('ourTeamLandPage')->with("success","Team Member Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        TeamLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('ourTeamLandPage')->with("success","Team Member Deactivated successfully..");        
    }

}