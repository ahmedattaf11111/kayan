<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterRequest;
use App\Services\Home\HomeService;

class HomeController extends Controller
{
    //This controller responsible for view items of some sections in the home
    
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function storeNewsletter(NewsletterRequest $request)
    {
        $this->homeService->storeNewsletter($request->input());
    }

    public function getSliders()
    {
        return $this->homeService->getSliders();
    }

    public function getSimpleAdvertises()
    {
        return $this->homeService->getSimpleAdvertises();
    }
}
