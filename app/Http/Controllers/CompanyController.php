<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function about()
    {
        return view('company.about');
    }

    public function pacra()
    {
        return view('company.pacra');
    }

    public function vision()
    {
        return view('company.vision-mission');
    }

    public function team()
    {
        return view('company.management-team');
    }

    public function corporate()
    {
        return view('company.corporate-information');
    }

    public function conduct()
    {
        return view('company.code-of-conduct');
    }

    public function waqf()
    {
        return view('company.waqf-deed');
    }

    public function ptf()
    {
        return view('company.ptf-policies');
    }

    public function privacy()
    {
        return view('company.privacy-policy');
    }
}
