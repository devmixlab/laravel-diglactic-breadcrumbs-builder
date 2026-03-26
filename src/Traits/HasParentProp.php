<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Traits;

use Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Breadcrumb;

/**
 * Trait to add a `parent` property and related helpers to a class.
 */
trait HasParentProp
{
    /**
     * The parent breadcrumb (optional).
     *
     * @var Breadcrumb|null
     */
    protected ?Breadcrumb $parent = null;

    /**
     * Set the parent breadcrumb.
     *
     * @param Breadcrumb $parent
     * @return $this
     */
    public function parent(Breadcrumb $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get the parent breadcrumb.
     *
     * @return Breadcrumb|null
     */
    public function getParent(): ?Breadcrumb
    {
        return $this->parent;
    }


    /**
     * Check if this item has a parent breadcrumb set.
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->parent !== null;
    }
}
