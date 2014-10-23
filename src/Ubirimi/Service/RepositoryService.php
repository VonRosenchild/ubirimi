<?php

namespace Ubirimi\Service;

class RepositoryService
{
    private $repositoryList = array();

    public function get($name)
    {
        if (isset($this->repositoryList[$name])) {
            return $this->repositoryList[$name];
        }

        $classNameComponents = explode(".", $name);
        if ($classNameComponents[0] == 'ubirimi') {
            $className = 'Ubirimi\\Repository\\' . ucfirst($classNameComponents[1]);

            if (isset($classNameComponents[2])) {
                $className .= '\\' . ucfirst($classNameComponents[2]);
            }
        } else {
            $className = 'Ubirimi\\' . ucfirst($classNameComponents[0]) .
                '\Repository\\' . ucfirst($classNameComponents[1]);
            if (isset($classNameComponents[2])) {
                $className .= '\\' . ucfirst($classNameComponents[2]);
            }
        }

        $this->repositoryList[$name] = new $className;

        return $this->repositoryList[$name];
    }
}
