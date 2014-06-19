<?php

    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

    Util::checkUserIsLoggedInAndRedirect();

    $linkTypeId = $_GET['id'];

    $issues = IssueLinkType::getByLinkTypeId($linkTypeId);
    $linkTypes = IssueLinkType::getByClientId($clientId);

    if ($issues) {
        echo '<div>Are you sure you want to delete this issue link type?</div>';

            echo '<div>There are currently ' . $issues->num_rows . ' matching links.</div>';
        if ($linkTypes->num_rows > 1) {
            echo '<input type="radio" checked="checked" name="action_remove_link" value="swap" id="swap_link_type" />';
            echo '<label for="swap_link_type">Swap current links to link type: </label>';
            echo '<select class="inputTextCombo" id="new_link_type">';
                while ($linkTypes && $linkType = $linkTypes->fetch_array(MYSQLI_ASSOC)) {
                    if ($linkType['id'] != $linkTypeId)
                        echo '<option value="' . $linkType['id'] . '">' . $linkType['name'] . '</option>';
                }
            echo '</select>';
            echo '<br />';
            echo '<input type="radio" name="action_remove_link" value="remove" id="swap_link_type_remove" />';
            echo '<label for="swap_link_type_remove">Remove all links</label>';
        } else {
            echo '<div>There are no other link types. All links will be removed.</div>';
        }
    } else {
        echo '<div>Are you sure you want to delete this issue link type?</div>';
    }