<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\Yongo\Repository\Issue\LinkType;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $linkTypeId = $request->get('id');

        $emptyName = false;
        $emptyOutwardDescription = false;
        $emptyInwardDescription = false;
        $linkTypeDuplicateName = false;

        $linkType = LinkType::getById($linkTypeId);

        if ($linkType['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $name = $linkType['name'];
        $outwardDescription = $linkType['outward_description'];
        $inwardDescription = $linkType['inward_description'];

        if ($request->request->has('edit_link_type')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $outwardDescription = Util::cleanRegularInputField($request->request->get('outward'));
            $inwardDescription = Util::cleanRegularInputField($request->request->get('inward'));

            if (empty($name))
                $emptyName = true;

            if (empty($outwardDescription))
                $emptyOutwardDescription = true;

            if (empty($inwardDescription))
                $emptyInwardDescription = true;

            // check for duplication
            $existingLinkType = LinkType::getByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name),
                $linkTypeId
            );

            if ($existingLinkType)
                $linkTypeDuplicateName = true;

            if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                LinkType::update($linkTypeId, $name, $outwardDescription, $inwardDescription, $currentDate);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Link Type ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue-features/linking');
            }
        }

        $menuSelectedCategory = 'system';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Link Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/Edit.php', get_defined_vars());
    }
}
