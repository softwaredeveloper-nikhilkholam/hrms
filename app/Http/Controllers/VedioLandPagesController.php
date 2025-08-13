<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VedioLandPage;
use Auth;


class VedioLandPagesController extends Controller
{
    public function index()
    {
        $vedios = VedioLandPage::paginate(10);
        return view('landingPages.vedios')->with(['vedios'=>$vedios, 'flag'=>1]);
    }

    public function create()
    {
        $vedios = VedioLandPage::paginate(10);
        return view('landingPages.vedios')->with(['vedios'=>$vedios, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        $vedio = new VedioLandPage();
        $vedio->link=$request->link;
        $vedio->description=$request->description;
        $vedio->copyWriter=$request->copyWriter;
        $vedio->updated_by=Auth::user()->username;
        $vedio->save();

        return redirect('/vedioLandPage')->with("success","Youtube Vedio Store successfully..");
    }

    public function edit($id)
    {
        $vedios = VedioLandPage::paginate(10);
        $vedio = VedioLandPage::find($id);
        return view('landingPages.vedios')->with(['vedios'=>$vedios, 'vedio'=>$vedio, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $vedio = VedioLandPage::find($id);
        $vedio->link=$request->link;
        $vedio->description=$request->description;
        $vedio->copyWriter=$request->copyWriter;
        $vedio->updated_by=Auth::user()->username;
        $vedio->save();
        return redirect('/vedioLandPage')->with("success","Youtube Vedio updated successfully..");
    }

    public function show($id)
    {
        $vedios = VedioLandPage::paginate(10);
        $vedio = VedioLandPage::find($id);
        return view('landingPages.vedios')->with(['vedio'=>$vedio, 'vedios'=>$vedios, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        VedioLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('vedioLandPage')->with("success","Youtube Vedio Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        VedioLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('vedioLandPage')->with("success","Youtube Vedio Deactivated successfully..");        
    }

}
