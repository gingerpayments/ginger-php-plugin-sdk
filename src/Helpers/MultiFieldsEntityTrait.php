<?php

namespace GingerPluginSdk\Helpers;

use GingerPluginSdk\Interfaces\AbstractCollectionContainerInterface;
use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

trait MultiFieldsEntityTrait
{
    public function getField($fieldName): mixed
    {
        return $this->$fieldName ?? null;
    }

    public function toArray(): array
    {
        $response = [];
        foreach (get_object_vars($this) as $var) {
            if ($var instanceof BaseField) {
                $response[$var->getPropertyName()] = $var->get();
            } elseif ($var instanceof MultiFieldsEntityInterface) {
                $response[$var->getField('property_name')] = $var->toArray();
            }
        }
        return array_filter($response);
    }
}