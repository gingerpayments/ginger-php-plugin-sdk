<?php

namespace GingerPluginSdk\Helpers;

use GingerPluginSdk\Exceptions\LackOfRequiredFieldsException;

trait HelperTrait
{
    protected function calculateValueInCents($value): int
    {
        return round((float)$value * 100);
    }

    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false): array|string
    {

        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    protected function isAssoc(array $array): bool
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    protected function camelCaseToDashes($string): string
    {
        for ($index = 0; $index < strlen($string); $index++) {
            if (ctype_upper($string[$index])) {
                $string[$index] = strtolower($string[$index]);
                if ($index > 0) {
                    $temporary_string = substr($string, 0, $index) . '_' . substr($string, $index, strlen($string));
                    $string = $temporary_string;
                }
            }
        }
        return $string;
    }
}