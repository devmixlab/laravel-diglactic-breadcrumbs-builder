<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder;

/**
 * Manages a stack of breadcrumb groups to help generate
 * hierarchical breadcrumb names and parent relationships.
 */
class Groups
{
    /**
     * Array of Group objects representing the current stack of groups.
     *
     * @var Group[]
     */
    private array $groups = [];

    /**
     * Add a new group to the stack.
     *
     * @param Group $group The group to add.
     */
    public function add(Group $group): void
    {
        array_push($this->groups, $group);
    }

    /**
     * Remove the last group from the stack.
     * Useful for leaving a nested group after a callback.
     */
    public function removeLastGroup(): void
    {
        array_pop($this->groups);
    }

    /**
     * Generate the full name for a breadcrumb by concatenating
     * all group names in the stack with the provided name.
     *
     * Example: If groups = ['dashboard.', 'vocabulary.'] and $name = 'create'
     * returns 'dashboard.vocabulary.create'
     *
     * @param string $name The base breadcrumb name.
     * @return string Full breadcrumb name including parent groups.
     */
    public function getFullName(string $name): string
    {
        if (empty($this->groups)) return $name;

        $names = '';
        foreach ($this->groups as $group) {
            if (!empty($group->name)) {
                $names .= $group->name;
            }
        }

        return $names . $name;
    }

    /**
     * Get the nearest parent Breadcrumb from the stack.
     * Iterates from last group to first to find a group with a parent.
     *
     * @return Breadcrumb|null Returns the parent Breadcrumb if found, otherwise null.
     */
    public function getParent(): ?Breadcrumb
    {
        if (empty($this->groups)) return null;

        // Iterate from last to first to find the most recent group with a parent
        for ($i = count($this->groups) - 1; $i >= 0; $i--) {
            $group = $this->groups[$i];
            if ($group->hasParent()) {
                return $group->getParent();
            }
        }

        return null;
    }
}
