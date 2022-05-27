<?php

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

/** @template T */
class AbstractCollection implements MultiFieldsEntityInterface
{
    private int $pointer = 0;
    private array $items = [];
    protected string $propertyName;

    /**
     * @template T
     * @var class-string T $classname
     */
    public function __construct(mixed $class_string, $propertyName)
    {
    }

    /** @var T $item */
    public function add(mixed $item)
    {
        $this->next();
        $this->items[$this->pointer] = $item;
    }

    /** @return T */
    public function get($position = null)
    {
        return $this->items[$position ?? $this->pointer];
    }

    /** @return array<T> */
    public function getAll(): array
    {
        return $this->items;
    }

    public function getField($fieldName): mixed
    {
        return $this->items[$fieldName] ?? "";
    }

    public function remove($index): static
    {
        unset($this->items[$index]);
        for ($i = $index; $i + 1 <= $this->count(); $i++) {

            $this->items[$i] = $this->items[$i + 1];
        }
        unset($this->items[$this->count()]);

        return $this;
    }

    public function getCurrentPointer(): int
    {
        return $this->pointer;
    }

    public function toArray(): array
    {
        $response = [];
        /** @var T $item */
        foreach ($this->items as $item) {
            if (method_exists($item, 'toArray')) {
                $response[] = $item->toArray();
            } else {
                $response[] = $item;
            }
        }
        return array_filter($response);
    }

    private function next()
    {
        $this->pointer++;
    }

    private function prev()
    {
        $this->pointer--;
    }

    public function clear()
    {
        $this->items = [];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function first()
    {
        return $this->items[1];
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}