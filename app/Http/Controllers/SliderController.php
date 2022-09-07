<?php

namespace App\Http\Controllers;

use App\Repositories\SliderRepository;

class SliderController extends Controller
{
    private $sliderRepository;
    public function __construct(SliderRepository $sliderRepositroy)
    {
        $this->sliderRepository = $sliderRepositroy;
    }
    public function index()
    {
        return $this->sliderRepository->getSliders();
    }
}
