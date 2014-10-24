<?php

namespace Ubirimi\Documentador\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FavouritesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;

            $settingsDocumentator = $this->getRepository(UbirimiClient::class)->getDocumentatorSettings($clientId);

            $documentatorUseAnonymous = $settingsDocumentator['anonymous_use_flag'];
            $documentatorAnonymousViewUserProfiles = $settingsDocumentator['anonymous_view_user_profile_flag'];

            if (!($documentatorUseAnonymous && $documentatorAnonymousViewUserProfiles)) {
                Util::signOutAndRedirect();
                die();
            }
        }

        $clientSettings = $this->getRepository(UbirimiClient::class)->getById($clientId);

        $userId = $request->get('id');
        $user = $this->getRepository(UbirimiUser::class)->getById($userId);

        if ($user['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'documentator';
        $groups = $this->getRepository(UbirimiGroup::class)->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $pages = $this->getRepository(Entity::class)->getFavouritePagesByClientIdAndUserId($clientId, $userId);
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Favourites';

        return $this->render(__DIR__ . '/../../Resources/views/user/Favourites.php', get_defined_vars());
    }
}