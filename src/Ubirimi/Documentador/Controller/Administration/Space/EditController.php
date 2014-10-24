<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiLog;
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

        $spaceId = $request->get('id');
        $backLink = isset($_GET['back']) ? $_GET['back'] : null;
        $space = $this->getRepository(Space::class)->getById($spaceId);
        $pages = $this->getRepository(Entity::class)->getAllBySpaceId($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $emptyCode = false;

        if ($request->request->has('edit_space')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $homepageId = Util::cleanRegularInputField($request->request->get('homepage'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            if (empty($code)) {
                $emptyCode = true;
            }

            if (!$emptyName && !$emptyCode) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(Space::class)->updateById($spaceId, $name, $code, $homepageId, $description, $currentDate);

                $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'UPDATE Documentador space ' . $name, $currentDate);

                if ($backLink == 'space_tools') {
                    return new RedirectResponse('/documentador/administration/space-tools/overview/' . $spaceId);
                } else {
                    return new RedirectResponse('/documentador/administration/spaces');
                }
            }
        }

        $menuSelectedCategory = 'doc_spaces';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/space/Edit.php', get_defined_vars());
    }
}