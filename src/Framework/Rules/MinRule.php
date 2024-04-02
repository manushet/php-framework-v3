<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class MinRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        if (empty($params[0])) {
            throw new \InvalidArgumentException("Minimum length not specified.");
        }

        $length = (int) $params[0];

        return empty($data[$field]) || ($data[$field] >= $length);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "must be at least {$params[0]}.";
    }
}