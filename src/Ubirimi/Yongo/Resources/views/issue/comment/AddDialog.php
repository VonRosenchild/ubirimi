<table class="table-modal">
    <tr>
        <td align="right" valign="top" width="64px">Comment</td>
        <td width="6px"></td>
        <td>
            <textarea class="inputTextAreaLarge mousetrap" id="issue_add_comment"></textarea>
        </td>
    </tr>
    <tr>
        <td align="right">Added by</td>
        <td></td>
        <td><?php echo $session->get('user')['first_name'] . ' ' . $session->get('user')['last_name'] ?></td>
    </tr>
</table>