<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder;

use Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Traits\HasNameProp;
use Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Traits\HasParentProp;

/**
 * Represents a breadcrumb group namespace.
 *
 * Can have an optional parent Breadcrumb and a name prefix.
 */
class Group
{
    use HasNameProp, HasParentProp;

    public function __construct(?string $name = null, ?Breadcrumb $parent = null) {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * Factory method to create a new Group with optional parameters.
     *
     * @param array{
     *     name?: string,
     *     parent?: Breadcrumb
     * } $params
     * @return self
     */
    static public function make(array $params = []): self
    {
        $name = $params['name'] ?? null;
        $parent = $params['parent'] ?? null;

        // Validate types
        if ($name !== null && (!is_string($name) || $name === '')) {
            throw new \InvalidArgumentException('Name must be a non-empty string.');
        }

        if ($parent !== null && !$parent instanceof Breadcrumb) {
            throw new \InvalidArgumentException('Parent must be an instance of Breadcrumb.');
        }

        return new self($name, $parent);
    }
}
