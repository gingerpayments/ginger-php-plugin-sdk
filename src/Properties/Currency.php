<?php

namespace GingerPluginSdk\Properties;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Exceptions\OutOfPatternException;
use GingerPluginSdk\Helpers\FieldsValidatorTrait;
use GingerPluginSdk\Interfaces\ValidateFieldsInterface;

final class Currency extends BaseField implements ValidateFieldsInterface
{
    use FieldsValidatorTrait;

    public function __construct($value)
    {
        parent::__construct('currency');
        $this->set($value);
    }

    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfPatternException
     */
    public function validate($value)
    {
        $this->validatePattern(
            value: $value,
            pattern: "/[A-Z]{3}/"
        );
    }
}