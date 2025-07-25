<?php

namespace App\Http\Controllers\ProductionTech;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VPNEmployeList_Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active', 'prodTech']);
    }

    public function index()
    {
        dd(auth()->user()->dept_category_id);
    }
}
