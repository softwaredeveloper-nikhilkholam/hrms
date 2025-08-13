<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditsController extends Controller
{
    public function index()
    {
        return view('storeAdmin.auditManagement.audit.list');
    }

    public function create()
    {
        return view('storeAdmin.auditManagement.audit.create');
    }

    public function auditProductEntry()
    {
        return view('storeAdmin.auditManagement.audit.productEntry');
    }

    public function auditViewProductList()
    {
        return view('storeAdmin.auditManagement.audit.viewProductList');
    }


}
