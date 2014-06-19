<?php

namespace Ubirimi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class UbirimiControllerResolver implements ControllerResolverInterface
{
    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller') . '::indexAction';

        $callable = $this->createController($controller);

        return $callable;
    }

    public function getArguments(Request $request, $controller)
    {
        return array($request, $request->getSession());
    }

    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array(new $class(), $method);
    }
}