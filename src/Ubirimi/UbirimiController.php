<?php

namespace Ubirimi;

use Ubirimi\Container\UbirimiContainer;

class UbirimiController
{
    public function getRepository($name) {

        $repositories = UbirimiContainer::getRepositories();

        $repository = null;
        if (array_key_exists($name, $repositories)) {
            $repository = $repositories[$name];
        } else {
            $classNameComponents = explode(".", $name);
            $className = 'Ubirimi\\' . ucfirst($classNameComponents[0]) .  '\Repository\\' . ucfirst($classNameComponents[1]) . '\\' . ucfirst($classNameComponents[2]);
            $repository = new $className;
            UbirimiContainer::setRepository($name, $repository);
        }

        return $repository;
    }

    public function render($path, $variables)
    {
        return array($path, $variables);
    }
}