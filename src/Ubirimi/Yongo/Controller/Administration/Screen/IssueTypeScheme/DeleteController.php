<?php

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\TypeScreenScheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $issueTypeScreenScheme = TypeScreenScheme::getMetaDataById($Id);

        TypeScreenScheme::deleteDataByIssueTypeScreenSchemeId($Id);
        TypeScreenScheme::deleteById($Id);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Issue Type Screen Scheme ' . $issueTypeScreenScheme['name'],
            $currentDate
        );

        return new Response('');
    }
}
