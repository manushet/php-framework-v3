<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\InRule;
use Framework\Rules\MinRule;
use Framework\Rules\UrlRule;
use Framework\Rules\EmailRule;
use Framework\Rules\MatchRule;
use Framework\Rules\NumericRule;
use Framework\Rules\RequiredRule;
use Framework\Rules\LengthMaxRule;
use Framework\Rules\LengthMinRule;
use Framework\Rules\DateFormatRule;

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
        $this->validator->addRule('lengthMin', new LengthMinRule());
        $this->validator->addRule('lengthMax', new LengthMaxRule());
        $this->validator->addRule('numeric', new NumericRule());
        $this->validator->addRule('dateFormat', new DateFormatRule());
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

    public function validateLogin(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    public function validateTransaction(array $formData)
    {
        $this->validator->validate($formData, [
            'description' => ['required', 'lengthMin:3', 'lengthMax:255'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'dateFormat:Y-m-d'],
        ]);
    }
}