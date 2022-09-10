<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Services\HomeService;

class HomeController extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function getCategories()
    {
        return $this->homeService->getCategories();
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
