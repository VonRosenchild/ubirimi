<?php

namespace Ubirimi\Yongo\Controller\Administration\Attachment;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class EditConfigurationController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';

        $settings = $this->getRepository('ubirimi.general.client')->getYongoSettings($session->get('client/id'));

        if ($request->request->has('update_configuration')) {
            $allowAttachmentsFlag = $request->request->get('allow_attachments_flag');

            $parameters = array(
                array(
                    'field' => 'allow_attachments_flag',
                    'value' => $allowAttachmentsFlag,
                    'type' => 'i'
                )
            );

            $this->getRepository('ubirimi.general.client')->updateProductSettings(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $parameters
            );

            $currentDate = Util::getServerCurrentDateTime();

            $this->getRepository('ubirimi.general.log')->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Attachment Settings',
                $currentDate
            );

            return new RedirectResponse('/yongo/administration/attachment-configuration');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Attachment Configuration';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/attachment/edit_configuration.php', get_defined_vars());
    }
}