<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $sprintId = $_GET['id'];
    $sprint = AgileSprint::getById($sprintId);
    $today = date("Y-m-d");
    $todayPlus2Weeks = date('Y-m-d', strtotime('+2 week', strtotime($today)));
?>
<table>
    <tr>
        <td>Sprint Name</td>
        <td>
            <input type="text" value="<?php echo $sprint['name'] ?>" class="inputText" id="sprint_name" />
        </td>
    </tr>
    <tr>
        <td valign="top">Start Date</td>
        <td>
            <input value="<?php echo $today ?>" type="text" class="inputText" id="sprint_start_date" style="width: 100px" />
            <div class="error" id="invalid_start_sprint_date"></div>
        </td>
    </tr>
    <tr>
        <td valign="top">End Date</td>
        <td>
            <input value="<?php echo $todayPlus2Weeks ?>" type="text" class="inputText" id="sprint_end_date" style="width: 100px" />
            <div class="error" id="invalid_end_sprint_date"></div>
        </td>
    </tr>
</table>