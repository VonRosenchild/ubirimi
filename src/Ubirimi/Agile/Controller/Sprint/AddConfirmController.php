<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $boardId = $_GET['board_id'];
    $lastSprint = AgileSprint::getLast($boardId);
    $suggestedName = '';

    if ($lastSprint) {
        $name = $lastSprint['name'];
        $nameComponents = explode(' ', $name);

        if (is_numeric($nameComponents[count($nameComponents) - 1])) {
            $value = $nameComponents[count($nameComponents) - 1];
            $value++;
            array_pop($nameComponents);
            if (count($nameComponents) == 1)
                $suggestedName = $nameComponents[0] . ' ' . $value;
            else
                $suggestedName = implode(' ', $nameComponents) . ' ' . $value;
        }
    } else {
        $suggestedName = 'Sprint 1';
    }
?>
<table>
    <tr>
        <td valign="top">Sprint Name</td>
        <td>
            <input type="text" class="inputText" value="<?php echo $suggestedName ?>" id="sprint_name" />
            <div class="error" id="empty_sprint_name"></div>
        </td>
    </tr>
</table>