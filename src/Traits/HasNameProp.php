<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Traits;

/**
 * Trait to add a `name` property and related helpers to a class.
 */
trait HasNameProp
{
    /**
     * The name of the item (e.g., a breadcrumb group or item).
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Set the name of this item.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name of this item.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Check if this item has a name set.
     *
     * @return bool
     */
    public function hasName(): bool
    {
        return !empty($this->name);
    }
}
