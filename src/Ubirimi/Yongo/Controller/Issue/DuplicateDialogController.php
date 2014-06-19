<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_GET['id'];
?>
Please enter the summary for the duplicated issue.
<br/>
(comments and attachments linked will not be duplicated)
<br/>
<input type="text" value="" id="summary" class="inputTextLarge"/>
<input id="issue_id" value="<?php echo $issueId ?>" type="hidden"/>