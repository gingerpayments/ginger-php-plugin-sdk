<?php

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Collections\AbstractCollection;
use GingerPluginSdk\Entities\Line;

final class OrderLines extends AbstractCollection
{
    const ITEM_TYPE = Line::class;
    public function __construct(Line ...$items)
    {
        $this->propertyName = 'order_lines';
        foreach ($items as $item) {
            $this->add($item);
        }
        parent::__construct(Line::class, 'order_lines');
    }

    public function addLine(Line $item)
    {
        $this->add($item);
    }

    public function removeLine($index)
    {
        $this->remove($index);
    }
}