<?php

namespace GingerPluginSdk\Interfaces;

interface MultiFieldsEntityInterface
{
    public function getField($fieldName): mixed;
    public function getPropertyName(): string;
    public function toArray(): array;
    public function update(array $data, $index = null);
}