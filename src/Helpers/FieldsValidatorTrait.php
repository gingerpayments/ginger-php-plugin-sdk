<?php

namespace GingerPluginSdk\Helpers;

use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Exceptions\OutOfPatternException;

trait FieldsValidatorTrait
{
    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfEnumException
     */
    public function validateEnum($value)
    {
        /** @var \GingerPluginSdk\Bases\BaseField $this */
        if (!in_array($value, $this->enum)) {
            throw new OutOfEnumException($this->getPropertyName(), $value, json_encode($this->enum));
        }
    }

    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfPatternException
     */
    public function validatePattern($value, $pattern)
    {
        /** @var \GingerPluginSdk\Bases\BaseField $this */
        if (!preg_match($pattern, $value)) {
            throw new OutOfPatternException($this->getPropertyName());
        }
    }
}