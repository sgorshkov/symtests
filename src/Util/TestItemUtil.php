<?php

declare(strict_types=1);

namespace App\Util;

final class TestItemUtil
{
    public static function generateName(): string
    {
        return bin2hex(random_bytes(10));
    }
}
