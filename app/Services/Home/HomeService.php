<?php

namespace App\Services\Home;

use App\Repositories\HomeRepository;

class HomeService
{
    private $homeRepository;
    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function storeNewsletter($newsLetterInput)
    {
        $this->homeRepository->storeNewsletter($newsLetterInput);
    }

    public function getSliders()
    {
        return $this->homeRepository->getSliders();
    }

    public function getSimpleAdvertises()
    {
        return $this->homeRepository->getSimpleAdvertises();
    }

}
