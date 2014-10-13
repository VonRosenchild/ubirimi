<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_overview';

    $timezoneData = explode("/", $session->get('client/settings/timezone'));
    $timezoneContinent = $timezoneData[0];
    $timeZoneContinents = array('Africa' => 1, 'America' => 2, 'Antarctica' => 4, 'Arctic' => 8, 'Asia' => 16,
                                'Atlantic' => 32, 'Australia' => 64, 'Europe' => 128, 'Indian' => 256, 'Pacific' => 512);
    $timeZoneCountry = $timezoneData[1];

    $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
    $client = $this->getRepository('ubirimi.general.client')->getById($clientId);

    if (isset($_POST['update_configuration'])) {

        $language = Util::cleanRegularInputField($_POST['language']);
        $timezone = Util::cleanRegularInputField($_POST['zone']);
        $titleName = Util::cleanRegularInputField($_POST['title_name']);
        $operatingMode = Util::cleanRegularInputField($_POST['mode']);

        $parameters = array(array('field' => 'title_name', 'value' => $titleName, 'type' => 's'),
                            array('field' => 'operating_mode', 'value' => $operatingMode, 'type' => 's'),
                            array('field' => 'language', 'value' => $language, 'type' => 's'),
                            array('field' => 'timezone', 'value' => $timezone, 'type' => 's'));

        $this->getRepository('ubirimi.general.client')->updateProductSettings($clientId, 'client_settings', $parameters);

        $session->set('client/settings/language', $language);
        $session->set('client/settings/timezone', $timezone);

        header('Location: /general-settings/view-general');
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Update';

    require_once __DIR__ . '/../Resources/views/EditSettings.php';