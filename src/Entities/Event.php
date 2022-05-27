<?php

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

class Event implements MultiFieldsEntityInterface
{
    use MultiFieldsEntityTrait;
    use SingleFieldTrait;

    private BaseField $occurred;
    private BaseField $event;
    private BaseField $source;
    private BaseField $noticed;
    private BaseField $id;

    public function __construct(
        string  $occurred,
        string  $event,
        string  $source,
        string  $noticed = null,
        ?string $id = null
    )
    {
        $this->occurred = $this->createFieldInDateTimeISO8601(
            propertyName: 'occurred',
            value: $occurred
        );
        $this->event = $this->createEnumeratedField(
            propertyName: 'event',
            value: $event,
            enum: [
                "new", "pending", "processing", "accepted", "completed", "cancelled", "error", "expired",
                "matched", "settled"
            ]
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
    }
}