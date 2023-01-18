<?php

namespace App\Http\Controllers;

use App\Models\FooterLink;
use App\Models\NeedHelp;
use App\Models\OurStore;
use App\Models\TopFooterSection;

class FooterController extends Controller
{
    public function getTopFooterSections()
    {
        return TopFooterSection::first();
    }
    public function getNeedHelp()
    {
        return NeedHelp::first();
    }
    public function getOurStore()
    {
        return OurStore::first();
    }
}
