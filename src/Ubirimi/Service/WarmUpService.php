<?php

namespace Ubirimi\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\SystemProduct;

/**
 * Warm up data in the session needed in a general context: Client or Customer.
 *
 */
class WarmUpService extends UbirimiService
{
    /**
     * Warms up the session with general information
     *
     * @param SessionInterface $session
     * @param $userData some general data about the user
     * @param null $yongoSettings
     * @param null $documentatorSettings
     */
    public function warmUpClient($userData, $warmYongoSettings = false, $warmDocumentadorSettings = false) {
        /* this is needed because of closure use */
        $session = $this->session;
        $this->warmUp($session, $userData);

        $clientProducts = $this->getRepository('ubirimi.general.client')->getProducts($userData['client_id'], 'array');

        array_walk($clientProducts, function($value, $key) use ($session) {
            $session->set("client/products/{$key}", $value);
        });

        /**
         * each product session information is under its namespace
         */
        if (true === $warmYongoSettings) {
            $yongoSettings = $this->getRepository('ubirimi.general.client')->getYongoSettings($userData['client_id']);

            array_walk($yongoSettings, function($value, $key) use ($session) {
                $session->set("yongo/settings/{$key}", $value);
            });
        }

        if (true === $warmDocumentadorSettings) {
            $documentadorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($userData['client_id']);

            array_walk($documentadorSettings, function($value, $key) use ($session) {
                $session->set("documentador/settings/{$key}", $value);
            });
        }

        /**
         * determine the selected project and product id
         */
        $projectsArray = $this->getRepository('ubirimi.general.client')->getProjectsByPermission(
            $userData['client_id'],
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        if ($projectsArray) {
            $session->set('selected_project_id', $projectsArray[0]['id']);
        }

        $session->set('selected_product_id', $session->get('client/products/sys_product_id'));

        $hasYongoGlobalAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasYongoGlobalSystemAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasYongoAdministerProjectsPermission = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($session->get('client/id'), $session->get('user/id'), Permission::PERM_ADMINISTER_PROJECTS);

        $session->set('user/yongo/is_global_administrator', $hasYongoGlobalAdministrationPermission);
        $session->set('user/yongo/is_global_system_administrator', $hasYongoGlobalSystemAdministrationPermission);
        $session->set('user/yongo/is_global_project_administrator', $hasYongoAdministerProjectsPermission);

        $hasDocumentatorGlobalAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_ADMINISTRATOR);
        $hasDocumentatorGlobalSystemAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasDocumentatorGlobalCreateSpace = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($session->get('client/id'), $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE);

        $session->set('user/documentator/is_global_administrator', $hasDocumentatorGlobalAdministrationPermission);
        $session->set('user/documentator/is_global_system_administrator', $hasDocumentatorGlobalSystemAdministrationPermission);
        $session->set('user/documentator/is_global_create_space', $hasDocumentatorGlobalCreateSpace);
    }

    /**
     * Warms up session data for a customer using HelpDesk
     *
     * @param SessionInterface $session
     * @param $userData
     */
    public function warmUpCustomer($userData) {
        $this->warmUp($this->session, $userData);

        $this->session->set('client/products', array(array('sys_product_id' => SystemProduct::SYS_PRODUCT_HELP_DESK)));

        $projects = $this->getRepository('ubirimi.general.client')->getProjects($userData['client_id'], 'array', null, true);
        if ($projects) {
            $this->session->set('selected_project_id', $projects[0]['id']);
        } else {
            $this->session->set("selected_product_id", SystemProduct::SYS_PRODUCT_HELP_DESK);
        }
    }

    /**
     * Warms up standard data that is general to both cases.
     *
     * @param $userData
     */
    private function warmUp($session, $userData)
    {
        $clientData = $this->getRepository('ubirimi.general.client')->getById($userData['client_id']);
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($userData['client_id']);
        $clientSmtpSettings = SMTPServer::getByClientId($userData['client_id']);

        /**
         * store user record information in session under "user" namespace
         */
        array_walk($userData, function($value, $key) use ($session) {
            $session->set("user/{$key}", $value);
        });

        array_walk($clientData, function($value, $key) use ($session) {
            $session->set("client/{$key}", $value);
        });

        array_walk($clientSettings, function($value, $key) use ($session) {
            $session->set("client/settings/{$key}", $value);
        });

        array_walk($clientSmtpSettings, function($value, $key) use ($session) {
            $session->set("client/settings/smtp/{$key}", $value);
        });

        date_default_timezone_set($session->get('client/settings/timezone'));
    }
}
