<?php

namespace App\Http\Controllers;

use App\Repositories\simpleAdvertiseRepository;

class SimpleAdvertiseController extends Controller
{
    private $simpleAdvertiseRepository;
    public function __construct(simpleAdvertiseRepository $simpleAdvertiseRepository)
    {
        $this->simpleAdvertiseRepository = $simpleAdvertiseRepository;
    }
    public function index()
    {
        return $this->simpleAdvertiseRepository->getSimpleAdvertises();
    }
}
