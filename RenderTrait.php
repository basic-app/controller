<?php

namespace BasicApp\Controller;

trait RenderTrait
{

    protected $viewsNamespace;

    protected function getViewsNamespace() : ?string
    {
        return $this->viewsNamespace;
    }

    protected function render(string $view, array $params = []) : string
    {
        $namespace = $this->getViewsNamespace();

        return view($namespace ?  $namespace . "\\" . $view : $view, $params);
    }

}