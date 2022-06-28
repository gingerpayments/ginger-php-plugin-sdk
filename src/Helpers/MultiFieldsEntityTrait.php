<?php

namespace GingerPluginSdk\Helpers;

use GingerPluginSdk\Interfaces\AbstractCollectionContainerInterface;
use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

trait MultiFieldsEntityTrait
{
    use HelperTrait;

    public function getField($fieldName): mixed
    {
        return $this->$fieldName ?? null;
    }


    public function getPropertyName(): string
    {
        return $this->propertyName ?? false;
    }

    public function toArray(): array
    {
        $response = [];
        foreach (get_object_vars($this) as $var) {
            if ($var instanceof BaseField) {
                $response[$var->getPropertyName()] = $var->get();
            } elseif ($var instanceof MultiFieldsEntityInterface) {
                $response[$var->getPropertyName()] = $var->toArray();
            }
        }

        return array_filter($response, function ($value) {
            return ($value !== null);
        });
    }

    public function filterAdditionalProperties($additionalProperties): void
    {
        foreach ($additionalProperties as $key => $value) {
            $key = $this->dashesToCamelCase($key, true);
            //Check if $additionalProperties is already an entities
            $path_to_properties = \GingerPluginSdk\Client::PROPERTIES_PATH . $key;
            if (class_exists($path_to_properties)) {
                if ($value instanceof $path_to_properties) {
                    $this->$key = $value;
                } else {
                    $this->$key = new $path_to_properties($value);
                }
                continue;
            }

            $path_to_collection = \GingerPluginSdk\Client::COLLECTIONS_PATH . $key;
            if (class_exists($path_to_collection)) {
                if ($value instanceof $path_to_collection) {
                    $this->$key = $value;
                } else if ($this->isAssoc($value)) {
                    $this->$key = new $path_to_collection($value);
                } else {
                    $this->$key = new $path_to_collection();
                    foreach ($value as $item) {
                        $this->$key->add($item);
                    }
                }
                continue;
            }

            $path_to_entities = \GingerPluginSdk\Client::ENTITIES_PATH . $key;
            if (class_exists($path_to_entities)) {
                if ($value instanceof $path_to_entities) {
                    $this->$key = $value;
                } else {
                    $this->$key = new $path_to_entities(...$value);
                }
                continue;
            }

            $this->$key = $this->createSimpleField(
                propertyName: $this->camelCaseToDashes($key),
                value: $value
            );

        }
    }

    public function update(...$attributes): static
    {
        $this->filterAdditionalProperties($attributes);
        return $this;
    }
}