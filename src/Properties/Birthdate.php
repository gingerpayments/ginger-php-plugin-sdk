<?php

namespace GingerPluginSdk\Properties;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Exceptions\OutOfPatternException;
use GingerPluginSdk\Interfaces\ValidateFieldsInterface;
use JetBrains\PhpStorm\Pure;

final class Birthdate extends BaseField implements ValidateFieldsInterface
{
    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfPatternException
     */
    public function validate($value)
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            throw new OutOfPatternException($this->getPropertyName());
        }
    }

    public function __construct(string $value)
    {
        parent::__construct('birthdate');
        $this->set($value);
    }

    #[Pure] public function __toString(): string
    {
        return $this->get();
    }
}