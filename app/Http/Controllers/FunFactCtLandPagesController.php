<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FunFactsLandPage;
use Auth;

class FunFactCtLandPagesController extends Controller
{
    public function index()
    {
        $facts = FunFactsLandPage::paginate(10);
        return view('landingPages.funfactscount')->with(['facts'=>$facts, 'flag'=>1]);
    }

    public function create()
    {
        $facts = FunFactsLandPage::paginate(10);
        return view('landingPages.funfactscount')->with(['facts'=>$facts, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        $fact = new FunFactsLandPage();
        $fact->projects=$request->projects;
        $fact->happyParents=$request->happyParents;
        $fact->awards=$request->awards;
        $fact->happyEmployees=$request->happyEmployees;
        $fact->updated_by=Auth::user()->username;
        $fact->save();

        return redirect('/funFactsCtLandPage')->with("success","Fun Fact Counts Store successfully..");
    }

    public function edit($id)
    {
        $facts = FunFactsLandPage::paginate(10);
        $fact = FunFactsLandPage::find($id);
        return view('landingPages.funfactscount')->with(['fact'=>$fact, 'facts'=>$facts, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $fact = FunFactsLandPage::find($id);
        $fact->projects=$request->projects;
        $fact->happyParents=$request->happyParents;
        $fact->awards=$request->awards;
        $fact->happyEmployees=$request->happyEmployees;
        $fact->updated_by=Auth::user()->username;
        $fact->save();
        return redirect('/funFactsCtLandPage')->with("success","Fun Fact Counts updated successfully..");
    }

    public function show($id)
    {
        $facts = FunFactsLandPage::paginate(10);
        $fact = FunFactsLandPage::find($id);
        return view('landingPages.funfactscount')->with(['fact'=>$fact, 'facts'=>$facts, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        FunFactsLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('funFactsCtLandPage')->with("success","Fun Facts Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        FunFactsLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('funFactsCtLandPage')->with("success","Fun Facts Deactivated successfully..");        
    }

}
