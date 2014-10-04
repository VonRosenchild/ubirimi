<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Util;

?>
<table width="100%">
    <tr>
        <td id="sectIssueTypes" width="74%" class="sectionDetail" colspan="3"><span class="headerPageText sectionDetailTitle">Description</span></td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td><span class="textLabel">Checkout URL:</span></td>
                    <td><?php echo UbirimiContainer::get()['subversion.url'] . Util::slugify($session->get('client/company_domain')) . '/' . Util::slugify($svnRepo['name']) . '/trunk' ?></td>
                </tr>
                <tr>
                    <td><span class="textLabel">Created by:</span></td>
                    <td><?php echo $svnRepo['first_name'] . ' ' . $svnRepo['last_name'] ?></td>
                </tr>
                <tr>
                    <td><span class="textLabel">Created at:</span></td>
                    <td><?php echo Util::getFormattedDate($svnRepo['date_created'], $clientSettings['timezone'], $clientSettings['timezone']) ?></td>
                </tr>
                <tr>
                    <td><span class="textLabel">Code:</span></td>
                    <td><?php echo $svnRepo['code'] ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>