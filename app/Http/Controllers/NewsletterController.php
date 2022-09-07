<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Repositories\NewsletterRepository;

class NewsletterController extends Controller
{
    private $newsletterRepository;
    public function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }
    public function store(NewsletterRequest $request)
    {
        $this->newsletterRepository->store($request->input());
    }
}
