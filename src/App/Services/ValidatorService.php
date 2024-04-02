<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\EmailRule;
use Framework\Rules\InRule;
use Framework\Rules\MatchRule;
use Framework\Rules\MinRule;
use Framework\Rules\RequiredRule;
use Framework\Rules\UrlRule;
use Framework\Validator;

class ValidatorService
{
    public Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->addRule('required', new RequiredRule());
        $this->validator->addRule('email', new EmailRule());
        $this->validator->addRule('min', new MinRule());
        $this->validator->addRule('in', new InRule());
        $this->validator->addRule('url', new UrlRule());
        $this->validator->addRule('match', new MatchRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'age' => ['required', 'min:18'],
            'country' => ['required', 'in:USA,Canada,Mexico'],
            'social-media-url' => ['required', 'url'],
            'password' => ['required'],
            'password-confirm' => ['required', 'match:password'],
            'tos' => ['required'],
        ]);
    }
}