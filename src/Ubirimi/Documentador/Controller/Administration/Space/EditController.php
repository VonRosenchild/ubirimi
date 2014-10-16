<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $spaceId = $_GET['id'];
        $backLink = isset($_GET['back']) ? $_GET['back'] : null;
        $space = $this->getRepository('documentador.space.space')->getById($spaceId);
        $pages = $this->getRepository('documentador.entity.entity')->getAllBySpaceId($spaceId);

        if ($space['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $emptyName = false;
        $emptyCode = false;

        if (isset($_POST['edit_space'])) {
            $name = Util::cleanRegularInputField($_POST['name']);
            $code = Util::cleanRegularInputField($_POST['code']);
            $homepageId = Util::cleanRegularInputField($_POST['homepage']);
            $description = Util::cleanRegularInputField($_POST['description']);

            if (empty($name))
                $emptyName = true;

            if (empty($code))
                $emptyCode = true;

            if (!$emptyName && !$emptyCode) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository('documentador.space.space')->updateById($spaceId, $name, $code, $homepageId, $description, $currentDate);

                $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'UPDATE Documentador space ' . $name, $currentDate);

                if ($backLink == 'space_tools') {
                    header('Location: /documentador/administration/space-tools/overview/' . $spaceId);
                } else {
                    header('Location: /documentador/administration/spaces');
                }
            }
        }

        $menuSelectedCategory = 'doc_spaces';

        require_once __DIR__ . '/../../../Resources/views/administration/space/Edit.php';
    }
}