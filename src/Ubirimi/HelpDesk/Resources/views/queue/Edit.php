<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/helpdesk/queues/' . $projectId . '/' . $queueId . '">Queues</a> > ' . $queue['name'] . ' > Update') ?>

    <div class="pageContent">
        <form name="add_queue" action="/helpdesk/queue/edit/<?php echo $queueId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $queue['name']; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($queueExists): ?>
                            <div class="error">A queue with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td><textarea name="description"
                                  class="inputTextAreaLarge"><?php echo $queue['description'] ?></textarea></td>
                </tr>
                <tr>
                    <td valign="top">Definition (Tickets to show)</td>
                    <td>
                        <textarea name="definition"
                                  id="queue_definition_sql"
                                  class="inputTextAreaLarge goal_autocomplete"><?php echo $queue['definition'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_queue" class="btn ubirimi-btn"><i class="icon-plus"></i> Update Queue</button>
                            <a class="btn ubirimi-btn" href="/helpdesk/queues/<?php echo $projectId ?>/<?php echo $queueId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php'; ?>
</body>
</html>