<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Collections;

use Closure;
use Illuminate\Support\Arr;
use Iterator;

abstract class AbstractCollection implements Iterator
{
    protected array $response = [];
    private int $position = 0;
    private array $items;

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->items = $this->get();
    }

    abstract public function get(): array;

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getMeta(): array
    {
        return $this->response['meta'] ?? [];
    }

    final public function first(): mixed
    {
        return Arr::first($this->items);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getLinks(): array
    {
        return $this->response['links'] ?? [];
    }

    final public function response(): array
    {
        return $this->response;
    }

    final public function where(callable $callback): array
    {
        if (!$callback instanceof Closure) {
            return $this->items;
        }

        return array_filter($this->items, $callback);
    }

    final public function map(callable $callback): array
    {
        $keys = array_keys($this->items);

        return array_map($callback, $this->items, $keys);
    }

    final public function rewind(): void
    {
        $this->position = 0;
    }

    final public function isDataEmpty(): bool
    {
        return $this->response()['data'] === [];
    }

    /**
     * @psalm-suppress MixedReturnStatement
     */
    final public function current()
    {
        return $this->items[$this->position];
    }

    final public function key()
    {
        return $this->position;
    }

    final public function next(): void
    {
        ++$this->position;
    }

    final public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
