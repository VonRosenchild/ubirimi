<?php

namespace Ubirimi\Documentador\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ActivityController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientSettings = $session->get('client/settings');
            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getById($clientId);
            $loggedInUserId = null;

            $settingsDocumentator = $this->getRepository(UbirimiClient::class)->getDocumentatorSettings($clientId);

            $documentatorUseAnonymous = $settingsDocumentator['anonymous_use_flag'];
            $documentatorAnonymousViewUserProfiles = $settingsDocumentator['anonymous_view_user_profile_flag'];

            if (!($documentatorUseAnonymous && $documentatorAnonymousViewUserProfiles)) {
                Util::signOutAndRedirect();
                die();
            }
        }

        $userId = $request->get('id');
        $user = $this->getRepository(UbirimiUser::class)->getById($userId);
        if ($user['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'documentator';

        $activities = $this->getRepository(UbirimiUser::class)->getDocumentatorActivityStream($userId);
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Activity';

        return $this->render(__DIR__ . '/../../Resources/views/user/Activity.php', get_defined_vars());
    }
}