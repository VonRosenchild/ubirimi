<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <tr>
        <td>
            <div>
                <a class="linkSubMenu" href="/helpdesk/all">All Help Desks</a>
            </div>
        </td>
    </tr>
    <?php if ($clientAdministratorFlag): ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/yongo/administration/project/add?helpdesk=true">Create Help Desk</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/helpdesk/administration">Administration</a>
                </div>
            </td>
        </tr>
    <?php endif ?>
</table>