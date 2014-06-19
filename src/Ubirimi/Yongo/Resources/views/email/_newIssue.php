<?php
    use Ubirimi\Container\UbirimiContainer;

    require_once __DIR__ . '/_header.php';
    $session = UbirimiContainer::get()['session'];
?>

<div style="font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">
    <a style="text-decoration: none;" href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
</div>

<br />

<table width="100%" cellpadding="2" border="0">
    <tr>
        <td width="150" style="color: #666666;">Issue Type:</td>
        <td><?php echo $this->issue['type_name'] ?></td>
    </tr>
    <tr>
        <td style="color: #666666;">Assignee:</td>
        <td>
            <?php if ($this->issue['ua_first_name']): ?>
                <?php echo $this->issue['ua_first_name'] . ' ' . $this->issue['ua_last_name'] ?>
            <?php else: ?>
                Unassigned
            <?php endif ?>
        </td>
    </tr>
    <tr>
        <td style="color: #666666;">Created:</td>
        <td><?php echo $this->issue['date_created'] ?></td>
    </tr>
    <?php if ($this->issue['due_date']): ?>
    <tr>
        <td style="color: #666666;">Due:</td>
        <td><?php echo $this->issue['due_date'] ?></td>
    </tr>
    <?php endif ?>
    <tr>
        <td style="color: #666666;" valign="top" width="80">Description:</td>
        <td><?php echo str_replace("\n",  '<br />', $this->issue['description']) ?></td>
    </tr>
    <tr>
        <td style="color: #666666;" width="80">Project:</td>
        <td><?php echo $this->issue['project_name'] ?></td>
    </tr>
    <tr>
        <td style="color: #666666;">Priority:</td>
        <td><?php echo $this->issue['priority_name'] ?></td>
    </tr>
    <tr>
        <td style="color: #666666;">Reporter:</td>
        <td><?php echo $this->issue['ur_first_name'] . ' ' . $this->issue['ur_last_name'] ?></td>
    </tr>
    <?php if ($this->versions_affected): ?>
    <tr>
        <td style="color: #666666;">Affects version/s:</td>
        <td>
            <?php
                $arr_string = array();
                while ($version = $this->versions_affected->fetch_array(MYSQLI_ASSOC)) {
                    $arr_string[] = $version['name'];
                }
            ?>
            <?php echo implode($arr_string, ', ') ?>
        </td>
    </tr>
    <?php endif ?>
    <?php if ($this->versions_fixed): ?>
    <tr>
        <td style="color: #666666;">Fix versions:</td>
        <td>
            <?php
                $arr_string = array();
                while ($version = $this->versions_fixed->fetch_array(MYSQLI_ASSOC)) {
                    $arr_string[] = $version['name'];
                }
            ?>
            <?php echo implode($arr_string, ', ') ?>
        </td>
    </tr>
    <?php endif ?>
    <?php if ($this->components): ?>
    <tr>
        <td style="color: #666666;">Components:</td>
        <td>
            <?php
                $arr_string = array();
                while ($component = $this->components->fetch_array(MYSQLI_ASSOC)) {
                    $arr_string[] = $component['name'];
                }
            ?>
            <?php echo implode($arr_string, ', ') ?>
        </td>
    </tr>
    <?php endif ?>
</table>

<?php require_once __DIR__ . '/_footer.php' ?>