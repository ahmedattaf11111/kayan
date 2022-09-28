<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PharmacistForm;
use App\Models\Supplier;

class FilterController extends Controller
{
    public function getSuppliers()
    {
        return Supplier::where("active", 1)->get();
    }
    public function getCompanies()
    {
        return Company::where("status", 1)->get();
    }
    public function getPharmacologicalForms()
    {
        return PharmacistForm::get();
    }
}
