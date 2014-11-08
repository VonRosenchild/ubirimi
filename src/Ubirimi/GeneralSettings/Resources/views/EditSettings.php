<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('GeneralSettings Settings > Update') ?>

    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>


            <form name="edit_configuration" method="post" action="/general-settings/edit-general">

                <table width="100%" cellspacing="0">
                    <tr>
                        <td class="headerPageText">Settings</td>
                    </tr>
                    <tr>
                        <td width="250" align="right">Title</td>
                        <td width="10"></td>
                        <td>
                            <input class="inputText" type="text" value="<?php echo $clientSettings['title_name'] ?>" name="title_name" />
                        </td>
                    </tr>
                    <tr>
                        <td width="250" align="right" valign="top">Mode</td>
                        <td width="10"></td>
                        <td>
                            <select name="mode" class="select2InputSmall" style="width: 100px">
                                <option value="private" <?php if ($clientSettings['operating_mode'] == 'private') echo 'selected="selected"' ?>>Private</option>
                                <option value="public" <?php if ($clientSettings['operating_mode'] == 'public') echo 'selected="selected"' ?>>Public</option>
                            </select>
                            <div>
                                Ubirimi can operate in two modes:
                                <br />
                                1. Public - Any user can sign up and post issues.
                                <br />
                                2. Private - Only administrators can create new users.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250" align="right">Base URL</td>
                        <td width="10"></td>
                        <td>
                            <input class="inputText" readonly="readonly" type="text" value="https://<?php echo $client['company_domain'] ?>.ubirimi.net" name="base_url" />
                        </td>
                    </tr>
                </table>
                <br />

                <div class="headerPageText">Internationalization</div>

                <table class="table table-hover table-condensed">
                    <tbody>
                        <tr>
                            <td width="250" align="right">Default Language</td>
                            <td>
                                <select name="language" class="select2InputSmall">
                                    <option value="english">English</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="250" align="right">Default user time zone</td>
                            <td>
                                <span>Region</span>
                                <select name="continent" class="select2InputSmall" id="continent_time_zone">
                                    <option value="1" <?php if ($timezoneContinent == 'Africa') echo 'selected="selected"' ?>>Africa</option>
                                    <option value="2" <?php if ($timezoneContinent == 'America') echo 'selected="selected"' ?>>America</option>
                                    <option value="4" <?php if ($timezoneContinent == 'Antarctica') echo 'selected="selected"' ?>>Antarctica</option>
                                    <option value="8" <?php if ($timezoneContinent == 'Arctic') echo 'selected="selected"' ?>>Arctic</option>
                                    <option value="16" <?php if ($timezoneContinent == 'Asia') echo 'selected="selected"' ?>>Asia</option>
                                    <option value="32" <?php if ($timezoneContinent == 'Atlantic') echo 'selected="selected"' ?>>Atlantic</option>
                                    <option value="64" <?php if ($timezoneContinent == 'Australia') echo 'selected="selected"' ?>>Australia</option>
                                    <option value="128" <?php if ($timezoneContinent == 'Europe') echo 'selected="selected"' ?>>Europe</option>
                                    <option value="256" <?php if ($timezoneContinent == 'Indian') echo 'selected="selected"' ?>>Indian</option>
                                    <option value="512" <?php if ($timezoneContinent == 'Pacific') echo 'selected="selected"' ?>>Pacific</option>
                                </select>
                                <span>Timezone</span>
                                <select name="zone" class="select2InputSmall" id="general_settings_zone">
                                    <?php
                                        $zones = DateTimeZone::listIdentifiers($timeZoneContinents[$timezoneContinent]);
                                        for ($i = 0; $i < count($zones); $i++) {
                                            $dateTimeZone0 = new DateTimeZone($zones[$i]);
                                            $dateTime0 = new DateTime("now", $dateTimeZone0);
                                            $timeOffset = $dateTimeZone0->getOffset($dateTime0);
                                            $prefix = ($timeOffset > 0) ? '+' : '';
                                            $offset = (($timeOffset / 60 / 60));
                                            $textSelected = '';
                                            if ($zones[$i] == $timezoneContinent . '/' . $timeZoneCountry)
                                                $textSelected = 'selected="selected"';
                                            echo '<option ' . $textSelected . ' value="' . $zones[$i] . '">' . str_replace(array("Africa/", "_"), array("", " "), $zones[$i]) . ' (GMT ' . $prefix . $offset . 'h)</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="padding-right: 5px; padding-left: 5px"></td>
                        <td align="left">
                            <button type="submit" name="update_configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Configuration</button>
                            <a class="btn ubirimi-btn" href="/general-settings/view-general">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>