<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
use Ubirimi\Repository\Log;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $emptyOutwardDescription = false;
        $emptyInwardDescription = false;
        $linkTypeDuplicateName = false;

        if ($request->request->has('new_link_type')) {
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
            $linkType = IssueLinkType::getByNameAndClientId($session->get('client/id'), mb_strtolower($name));

            if ($linkType)
                $linkTypeDuplicateName = true;

            if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
                $currentDate = Util::getServerCurrentDateTime();

                IssueLinkType::add(
                    $session->get('client/id'),
                    $name,
                    $outwardDescription,
                    $inwardDescription,
                    $currentDate
                );

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Link Type',
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue-features/linking');
            }
        }

        $menuSelectedCategory = 'system';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Link Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/Add.php', get_defined_vars());
    }
}
