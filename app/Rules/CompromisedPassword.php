<?php

namespace App\Rules;

use App\Services\PasswordCheckService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;


class CompromisedPassword implements Rule
{
    protected $passwordCheckService;

    public function __construct()
    {
        $this->passwordCheckService = new PasswordCheckService();
    }

    // Inside the passes method
    public function passes($attribute, $value)
    {
        return Cache::remember("password_check_{$value}", 60, function () use ($value) {
            return !$this->passwordCheckService->isCompromised($value);
        });
    }

    public function message()
    {
        return 'This password has been compromised in a data breach. Please choose a different password.';
    }
}
