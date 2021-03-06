<?php
declare(strict_types=1);

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;

/**
 * @template T
 * @phpstan-template T
 */
class AbstractCollection implements MultiFieldsEntityInterface
{
    private int $pointer = 0;
    /** @var T[] */
    private array $items = [];

    /**
     * @param string $propertyName
     */
    public function __construct(protected string $propertyName)
    {
    }

    /**
     * @param array $data
     * @param null $index
     *
     */
    public function update(mixed $data, $index = null): static
    {
        $item = $this->get($index);

        if ($item instanceof MultiFieldsEntityInterface) {
            $item->update(...$data);
        } else {
            $this->items[$index] = $data;
        }
        return $this;
    }

    /**
     * @param T $item
     *
     * @phpstan-param T $item
     */
    public function add(mixed $item): void
    {
        $this->next();
        $this->items[$this->pointer] = $item;
    }

    /**
     * @param int|string $position
     * @return T|null
     *
     * @phpstan-param int|string $position
     * @phpstan-return T|null
     */
    public function get($position = null)
    {
        return $this->items[$position ?? $this->pointer];
    }

    /**
     * @return T[]
     *
     * @phpstan-return T[]
     */
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