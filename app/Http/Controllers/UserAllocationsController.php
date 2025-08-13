<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SideMenu;
use App\EmpMenu;
use App\EmpDet;
use DB;
use Auth;

class UserAllocationsController extends Controller
{
    public function index()
    {
        $menus = SideMenu::leftJoin('emp_menus', 'emp_menus.menuId', '=', 'side_menus.id')
        ->select('side_menus.*')
        ->get();

        return view('admin.userAllocations.list')->with(['menus'=>$menus]);
    }

    public function search(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode)
        {
            $empId = EmpDet::where('empCode', $empCode)->value('id');
            $menus = SideMenu::leftJoin('emp_menus', 'emp_menus.menuId', '=', 'side_menus.id')
            ->select('side_menus.*')
            // ->where('emp_menus.empId', $empId)
            ->get();

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
