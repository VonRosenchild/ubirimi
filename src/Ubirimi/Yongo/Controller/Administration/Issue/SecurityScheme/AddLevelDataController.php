<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;


class AddLevelDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $levelId = $request->get('id');

        $level = IssueSecurityScheme::getLevelById($levelId);
        $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($level['issue_security_scheme_id']);

        if ($issueSecurityScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'));
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roles = $this->getRepository('yongo.permission.role')->getByClient($session->get('client/id'));

        if ($request->request->has('confirm_new_data')) {

            $levelDataType = $request->request->get('type');

            $user = $request->request->get('user');
            $group = $request->request->get('group');
            $role = $request->request->get('role');
            $currentDate = Util::getServerCurrentDateTime();

            if ($levelDataType) {

                // check for duplicate information
                $duplication = false;
                $dataLevel = IssueSecurityScheme::getDataByLevelId($levelId);

                if ($dataLevel) {

                    while ($data = $dataLevel->fetch_array(MYSQLI_ASSOC)) {
                        if ($data['group_id'] && $data['group_id'] == $group)
                            $duplication = true;
                        if ($data['user_id'] && $data['user_id'] == $user)
                            $duplication = true;
                        if ($data['permission_role_id'] && $data['permission_role_id'] && $role)
                            $duplication = true;

                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_PROJECT_LEAD)
                            if ($data['project_lead'])
                                $duplication = true;
                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_CURRENT_ASSIGNEE)
                            if ($data['current_assignee'])
                                $duplication = true;
                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_REPORTER)
                            if ($data['reporter'])
                                $duplication = true;
                    }
                }
                if (!$duplication) {
                    IssueSecurityScheme::addLevelData($levelId, $levelDataType, $user, $group, $role, $currentDate);

                    $this->getRepository(UbirimiLog::class)->add(
                        $session->get('client/id'),
                        SystemProduct::SYS_PRODUCT_YONGO,
                        $session->get('user/id'),
                        'UPDATE Yongo Issue Security Scheme Level ' . $level['name'],
                        $currentDate
                    );
                }
            }

            return new RedirectResponse('/yongo/administration/issue-security-scheme-levels/' . $issueSecurityScheme['id']);
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme Level Data';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AddLevelData.php', get_defined_vars());
    }
}
