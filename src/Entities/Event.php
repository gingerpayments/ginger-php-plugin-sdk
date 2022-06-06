<?php

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Helpers\HelperTrait;
use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

class Event implements MultiFieldsEntityInterface
{
    use MultiFieldsEntityTrait;
    use SingleFieldTrait;
    use HelperTrait;

    private BaseField $occurred;
    private BaseField $event;
    private BaseField $source;
    private BaseField $noticed;
    private BaseField $id;

    public function __construct(
        string  $occurred,
        string  $event,
        ?string  $source = null,
        string  $noticed = null,
        ?string $id = null,
        mixed ...$additionalArguments
    )
    {
        $this->occurred = $this->createFieldInDateTimeISO8601(
            propertyName: 'occurred',
            value: $occurred
        );
        $this->event = $this->createSimpleField(
            propertyName: 'event',
            value: $event
        );
        $this->source = $this->createSimpleField(
            propertyName: 'source',
            value: $source
        );

        if ($noticed) $this->noticed = $this->createFieldInDateTimeISO8601(
            propertyName: 'noticed',
            value: $noticed
        );

        if ($id) $this->id = $this->createSimpleField(
            propertyName: 'id',
            value: $id
        );

        if ($additionalArguments) {
            foreach ($additionalArguments as $key => $value) {
                $this->$key = $this->createSimpleField(
                    propertyName: $this->camelCaseToDashes($key),
                    value: $value
                );
            }
        }
    }
}