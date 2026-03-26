<?php

namespace Devmixlab\LaravelDiglacticBreadcrumbsBuilder;

use Devmixlab\LaravelDiglacticBreadcrumbsBuilder\Traits\HasParentProp;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

class Breadcrumb
{
    use HasParentProp;

    /**
     * Callback to modify this breadcrumb dynamically when generating the trail.
     * Example: set dynamic route parameters based on model data.
     */
    protected ?\Closure $modifyCallback = null;

    /**
     * Array of parameters to pass to route() for generating the URL.
     */
    protected array $routerParams = [];

    /**
     * The constructor sets the basic properties of the breadcrumb.
     *
     * @param string $name  The unique name/identifier for this breadcrumb.
     * @param string $title The title to display for this breadcrumb.
     */
    public function __construct(protected string $name, protected string $title) {}

    /**
     * Set a callback to modify this breadcrumb dynamically when generating the trail.
     *
     * @param callable $modify Callback function with signature:
     *                         function(Breadcrumb $breadcrumb, Trail $trail, ...$args)
     * @return $this
     */
    public function modify(callable $modify): self
    {
        $this->modifyCallback = $modify;
        return $this;
    }

    /**
     * Register this breadcrumb with Diglactic Breadcrumbs.
     * Calls the modify callback, sets parent if available, and pushes the breadcrumb title & route.
     */
    public function dispatch(): void
    {
        Breadcrumbs::for($this->name, function (Trail $trail, ...$args) {
            // Run user-defined modification callback if set
            if ($this->modifyCallback) {
                ($this->modifyCallback)($this, $trail, ...$args);
            }

            // Set parent breadcrumb if available
            if ($this->parent)
                $trail->parent($this->parent->name);

            // Push the breadcrumb with title and route
            $trail->push($this->title, $this->getRoute());
        });
    }

    /**
     * Get the route URL for this breadcrumb.
     * Uses Laravel's route() helper and passes stored router parameters.
     *
     * @return string
     */
    protected function getRoute(): string
    {
        return route($this->name, $this->routerParams);
    }

    /**
     * Set the route parameters for this breadcrumb.
     * Useful when a breadcrumb URL depends on a model instance.
     *
     * @param array $params
     * @return $this
     */
    public function routerParams(array $params): self
    {
        $this->routerParams = $params;
        return $this;
    }
}
