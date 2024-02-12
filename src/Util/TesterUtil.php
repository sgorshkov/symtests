<?php

declare(strict_types=1);

namespace App\Util;

use Symfony\Component\Console\Exception\InvalidArgumentException;

final class TesterUtil
{
    private const string ERROR_MESSAGE = 'Answer not exists. You should choose answer(s) by number (ex.: 1,3).';

    public static function multiselectValidator(array $choices): callable
    {
        return static function ($selected) use ($choices) {
            if (!preg_match('/^[^,]+(?:,[^,]+)*$/', (string)$selected, $matches)) {
                throw new InvalidArgumentException(self::ERROR_MESSAGE);
            }

            $multiselect = explode(',', (string)$selected);;

            foreach ($multiselect as $value) {
                if (!is_numeric($value)) {
                    throw new InvalidArgumentException(self::ERROR_MESSAGE);
                }
                $result = $choices[$value] ?? null;
                if (null === $result) {
                    throw new InvalidArgumentException(self::ERROR_MESSAGE);
                }
            }

            return $multiselect;
        };
    }
}
