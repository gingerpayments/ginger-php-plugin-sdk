<?php

namespace GingerPluginSdk\Properties;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Helpers\FieldsValidatorTrait;
use GingerPluginSdk\Interfaces\ValidateFieldsInterface;

class Status extends BaseField implements ValidateFieldsInterface
{
    use FieldsValidatorTrait;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->enum = [
            "new",
            "pending",
            "processing",
            "accepted",
            "captured",
            "completed",
            "cancelled",
            "error",
            "expired"
        ];
        parent::__construct('status');
        $this->set($value);
    }

    /**
     * @throws \GingerPluginSdk\Exceptions\OutOfEnumException
     */
    public function validate($value)
    {
        $this->validateEnum($value);
    }
}