<?php

namespace GingerPluginSdk\Helpers;

use GingerPluginSdk\Collections\AbstractCollection;

trait HelperTrait
{
    public static function isSameType($argument1, $argument2): bool
    {
        if (is_object($argument1)) {
            if (is_object($argument2)) {
                return get_class($argument1) == get_class($argument2);
            }
            return false;
        }

        return gettype($argument1) == gettype($argument2);
    }

    public static function isCollection(string $className): bool
    {
        return array_key_exists(AbstractCollection::class, class_parents($className));
    }

    public function calculateValueInCents($value): int
    {
        return round((float)$value * 100);
    }

    protected static function dashesToCamelCase($string, $capitalizeFirstCharacter = false): array|string
    {

        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    protected static function isAssoc(array $array): bool
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