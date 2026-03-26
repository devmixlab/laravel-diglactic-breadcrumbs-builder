<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder;

/**
 * Class BreadcrumbsBuilder
 *
 * Provides a structured way to define and dispatch multiple breadcrumbs.
 * Supports grouping of breadcrumbs, parent-child relationships, and dynamic route parameters.
 */
class BreadcrumbsBuilder
{
    /**
     * Array of all added Breadcrumb instances.
     *
     * @var Breadcrumb[]
     */
    public array $items = [];

    /**
     * Internal helper to manage breadcrumb groups and parent relationships.
     *
     * @var Groups
     */
    private Groups $groups;

    /**
     * Constructor initializes the group manager.
     */
    public function __construct()
    {
        $this->groups = new Groups();
    }

    /**
     * Add a new breadcrumb to the builder.
     *
     * Automatically attaches the current parent breadcrumb (from Groups) if present.
     *
     * @param string $name  Unique name/identifier for the breadcrumb.
     * @param string $title Title to display for the breadcrumb.
     * @return Breadcrumb The created breadcrumb instance for further modification (e.g., dynamic routes).
     */
    public function add(string $name, string $title): Breadcrumb
    {
        // Combine with current group prefix to create full breadcrumb name
        $name = $this->groups->getFullName($name);

        // Create the breadcrumb
        $b = new Breadcrumb($name, $title);

        // Attach parent if a parent is currently set in the group
        $parent = $this->groups->getParent();
        if ($parent) $b->parent($parent);

        // Add the breadcrumb to the collection
        array_push($this->items, $b);

        return $b;
    }

    /**
     * Define a group of breadcrumbs under a shared context.
     *
     * @param array    $params   Group configuration, e.g. ['name' => 'dashboard.', 'parent' => $parentBreadcrumb]
     * @param callable $callback Callback that receives this builder instance to define breadcrumbs inside the group.
     * @return $this
     */
    public function group(array $params, callable $callback): self
    {
        // Register a new group context
        $this->groups->add(Group::make($params));

        // Execute callback to add breadcrumbs within this group
        $callback($this);

        // Remove the group context after adding breadcrumbs
        $this->groups->removeLastGroup();

        return $this;
    }

    /**
     * Register all breadcrumbs with Diglactic Breadcrumbs.
     *
     * Iterates through all added breadcrumbs and calls their `dispatch()` method.
     */
    public function dispatch(): void
    {
        foreach ($this->items as $item)
            $item->dispatch();
    }
}
