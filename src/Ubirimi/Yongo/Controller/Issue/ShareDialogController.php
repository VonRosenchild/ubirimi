<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_GET['id'];

    $users = User::getByClientId($clientId, 0);
    $subdomain = Util::getSubdomain();
?>
<table>
    <tr>
        <td>
            <div>Link to Issue</div>
            <input type="text" class="inputTextLarge" value="https://<?php echo $subdomain ?>.ubirimi.net/yongo/issue/<?php echo $issueId ?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td>
            <div>User</div>
            <select style="width: 100%;" name="user_to_share[]" id="user_to_share" class="inputTextCombo chzn-select" multiple="multiple">
                <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                    <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                <?php endwhile ?>
            </select>
            <div class="error" id="share_no_user_selected"></div>
        </td>
    </tr>
    <tr>
        <td>
            <div>Note</div>
            <textarea id="share_issue_note" rows="20" class="inputTextAreaLarge" style="height: 400px"></textarea>
        </td>
    </tr>
</table>