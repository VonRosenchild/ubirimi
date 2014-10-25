<?php

namespace Ubirimi\Service;

class RepositoryService
{
    private $repositoryList = array();

    public function get($fullyQualifiedClassName)
    {
        if (isset($this->repositoryList[$fullyQualifiedClassName])) {
            return $this->repositoryList[$fullyQualifiedClassName];
        }

        $this->repositoryList[$fullyQualifiedClassName] = new $fullyQualifiedClassName;

        return $this->repositoryList[$fullyQualifiedClassName];
    }
}
