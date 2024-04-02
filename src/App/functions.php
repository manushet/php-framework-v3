<?php

declare(strict_types=1);

function dd(mixed $output): void
{
    echo "<br><pre>";
    print_r($output);
    echo "</pre><br>";
}

function redirectTo($path)
{
    header("Location: {$path}");
    http_response_code(302);
    exit();
}