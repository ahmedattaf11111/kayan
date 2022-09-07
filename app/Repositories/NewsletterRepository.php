<?php

namespace App\Repositories;

use App\Models\Newsletter;

class NewsletterRepository
{
    public function store($newsLetterInput)
    {
        Newsletter::create($newsLetterInput);
    }
}
