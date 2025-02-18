<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(){
        $brands = Brand::latest()->paginate(5);
        return view('admin.brands.index',compact('brands'));
    }
}
