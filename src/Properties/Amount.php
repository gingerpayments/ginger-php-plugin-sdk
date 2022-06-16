<?php

namespace GingerPluginSdk\Properties;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Exceptions\OutOfDiapasonException;
use GingerPluginSdk\Helpers\FieldsValidatorTrait;
use GingerPluginSdk\Helpers\HelperTrait;
use GingerPluginSdk\Interfaces\ValidateFieldsInterface;
use GingerPluginSdk\Interfaces\ValueInCentsInterface;

class Amount extends BaseField implements ValueInCentsInterface, ValidateFieldsInterface
{
    use HelperTrait, FieldsValidatorTrait;

    public function __construct(float|int $value)
    {
        $this->propertyName = 'amount';
        $this->set($this->calculateValueInCents($value));
        parent::__construct('amount');
    }

    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfDiapasonException
     */
    public function validate($value)
    {
        if ($value < 1) throw new OutOfDiapasonException(
            propertyName: $this->propertyName,
            value: $value,
            min: 1);
    }
}