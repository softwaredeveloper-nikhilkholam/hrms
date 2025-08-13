<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LeavePaymentPolicy;
use App\ContactusLandPage;
use Auth;   

class LeavePaymentPolicyController extends Controller
{
    public function index()
    {
        $policies = LeavePaymentPolicy::orderBy('created_at', 'desc')->get();
        return view('admin.leavePaymentPolicies.index', compact('policies'));
    }

    public function create()
    {
        $months = [
            'January'   => 'January',
            'February'  => 'February',
            'March'     => 'March',
            'April'     => 'April',
            'May'       => 'May',
            'June'      => 'June',
            'July'      => 'July',
            'August'    => 'August',
            'September' => 'September',
            'October'   => 'October',
            'November'  => 'November',
            'December'  => 'December',
        ];
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName', 'asc')->pluck('branchName', 'id');
        return view('admin.leavePaymentPolicies.create', compact('branches','months'));
    }

    public function store(Request $request) 
    {
        $policy = new LeavePaymentPolicy;
        $policy->section = $request->section;
        $policy->startMonth = $request->startMonth;
        $policy->endMonth = $request->endMonth;
        $policy->paymentDays = $request->paymentDays;
        $policy->branchId = $request->branchId ? ContactusLandPage::where('id', $request->branchId)->value('branchName') : null;
        $policy->yearStatus = $request->yearStatus;
        $policy->updated_by = Auth::user()->username;
        $policy->save();
        
        return redirect('/leavePaymentPolicy')->with('success', 'New Payment Policy added successfully!!!');
    }

    public function edit($id)
    {
        $policy = LeavePaymentPolicy::findOrFail($id);
        $months = [
            'January'   => 'January',
            'February'  => 'February',
            'March'     => 'March',
            'April'     => 'April',
            'May'       => 'May',
            'June'      => 'June',
            'July'      => 'July',
            'August'    => 'August',
            'September' => 'September',
            'October'   => 'October',
            'November'  => 'November',
            'December'  => 'December',
        ];
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName', 'asc')->pluck('branchName', 'id');
        return view('admin.leavePaymentPolicies.edit', compact('policy','branches','months'));
    }

    public function update(Request $request, $id)
    {
        $policy = LeavePaymentPolicy::findOrFail($id);
        $policy->section = $request->section;
        $policy->startMonth = $request->startMonth;
        $policy->endMonth = $request->endMonth;
        $policy->paymentDays = $request->paymentDays;
        $policy->branchId = $request->branchId ? ContactusLandPage::where('id', $request->branchId)->value('branchName') : null;
        $policy->yearStatus = $request->yearStatus;
        $policy->updated_by = Auth::user()->username;
        $policy->save();
        
        return redirect('/leavePaymentPolicy')->with('success', 'Old Payment Policy updated successfully!!!');

    }

    public function toggleActive($id)
    {
        $policy = LeavePaymentPolicy::findOrFail($id);
        $policy->active = !$policy->active;
        $policy->save();

        return redirect()->back()->with('success', 'Policy status updated.');
    }
}
