<?php

namespace Ubirimi\Yongo\Controller\Administration\Field\ConfigurationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Repository\Log;
use Ubirimi\Yongo\Repository\Issue\IssueType;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        if ($request->request->has('add_field_configuration_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $fieldConfigurationScheme = new FieldConfigurationScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $fieldConfigurationSchemeId = $fieldConfigurationScheme->save($currentDate);

                $issueTypes = IssueType::getAll($session->get('client/id'));
                while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
                    FieldConfigurationScheme::addData($fieldConfigurationSchemeId, null, $issueType['id'], $currentDate);
                }

                Log::add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Field Configuration Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/field-configurations/schemes');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Event';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration_scheme/Add.php', get_defined_vars());
    }
}
