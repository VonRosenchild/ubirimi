<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class EntityController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $entities = Entity::getAll(array('sort_by' => 'documentator_entity.date_created', 'sort_order' => 'desc'));

        $selectedOption = 'entity';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Entity.php', get_defined_vars());
    }
}
