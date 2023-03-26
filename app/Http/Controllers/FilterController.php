<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\PharmacistForm;
use App\Models\Supplier;

class FilterController extends Controller
{
    public function getCompanies()
    {
        return Company::get();
    }
    public function getCategories()
    {
        return Category::get();
    }

    public function getPharmacologicalForms()
    {
        return PharmacistForm::get();
    }
}
