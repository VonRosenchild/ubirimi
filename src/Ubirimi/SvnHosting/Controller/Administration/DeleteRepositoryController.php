<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteRepositoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $Id = $request->request->get('svn_id');

        $repo = $this->getRepository('svnHosting.repository')->getById($Id);

        $this->getRepository('svnHosting.repository')->deleteById($Id);

        $this->getRepository('svnHosting.repository')->updateHtpasswd($repo['id'], $session->get('client/company_domain'));
        $this->getRepository('svnHosting.repository')->updateAuthz();

        /* delete the content from hdd */
        $companyDomain = Util::getSubdomain();
        $path = UbirimiContainer::get()['subversion.path'] . Util::slugify($companyDomain) . '/' . Util::slugify($repo['name']);
        system("rm -rf $path");

        /* refresh apache config */
        $this->getRepository('svnHosting.repository')->refreshApacheConfig();

        $this->getRepository('ubirimi.general.log')->add(
            $clientId,
            SystemProduct::SYS_PRODUCT_SVN_HOSTING,
            $loggedInUserId,
            'DELETE SVN Repository ' . $repo['name'],
            Util::getServerCurrentDateTime()
        );

        return new Response('');
    }
}
