<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactusLandPage;
use Auth;

class ContactusLandPagesController extends Controller
{
    public function index()
    {
        $contacts = ContactusLandPage::all();
        return view('landingPages.contactus')->with(['contacts'=>$contacts, 'flag'=>1]);
    }

    public function create()
    {
        $contacts = ContactusLandPage::all();
        return view('landingPages.contactus')->with(['contacts'=>$contacts, 'flag'=>1]);
    }

    public function store(Request $request)
    {
        if(ContactusLandPage::where('branchName', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Branch Name is alredy exist..");
        }

        $contact = new ContactusLandPage();
        $contact->branchName=$request->name;
        $contact->subTitle=$request->subTitle;
        $contact->address=$request->address;
        $contact->email=$request->email;
        $contact->phoneNo=$request->phoneNo;
        $contact->link=$request->mapLink;
        $contact->updated_by=Auth::user()->username;
        $contact->save();

        return redirect('/contactusLandPage')->with("success","Contact Us Information Store successfully..");
    }

    public function edit($id)
    {
        $contacts = ContactusLandPage::paginate(10);
        $contact = ContactusLandPage::find($id);
        return view('landingPages.contactus')->with(['contact'=>$contact, 'contacts'=>$contacts, 'flag'=>2]);
    }

    public function update(Request $request, $id)
    {
        $contact = ContactusLandPage::find($id);
        $contact->branchName=$request->name;
        $contact->subTitle=$request->subTitle;
        $contact->address=$request->address;
        $contact->email=$request->email;
        $contact->phoneNo=$request->phoneNo;
        $contact->link=$request->mapLink;
        $contact->updated_by=Auth::user()->username;
        $contact->save();

        return redirect('contactusLandPage')->with("success","Contact Us Information Updated successfully..");
    }

    public function show($id)
    {
        $contacts = ContactusLandPage::paginate(10);
        $contact = ContactusLandPage::find($id);
        return view('landingPages.contactus')->with(['contact'=>$contact, 'contacts'=>$contacts, 'flag'=>3]);
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        ContactusLandPage::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('contactusLandPage')->with("success","Contact Us Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        ContactusLandPage::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('contactusLandPage')->with("success","Contact Us Deactivated successfully..");        
    }
}
