<table>
    <tr>
        <td colspan="2">Change permissions for user <?php echo $user['first_name'] . ' ' . $user['last_name'] ?> in repository <?php echo $svnRepo['name'] ?></td>
    </tr>
    <tr>
        <td width="150">Has Read</td>
        <td><input type="checkbox" id="has_read" value="1" <?php if (1 == $user['has_read']): ?>checked="checked" <?php endif ?> /></td>
    </tr>
    <tr>
        <td>Has Write</td>
        <td><input type="checkbox" id="has_write" value="1" <?php if (1 == $user['has_write']): ?>checked="checked" <?php endif ?>/></td>
    </tr>
</table>