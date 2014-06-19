<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../QuickNotes/Resources/views/_header.php';
?>
<style type="text/css">
    html, body {
        height: 100%;
    }
</style>

<body>
    <?php require_once __DIR__ . '/../../../../QuickNotes/Resources/views/_menu.php'; ?>
    <div class="pageContent">
        <?php
            Util::renderBreadCrumb('Quick Notes > All Notes');
        ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <input id="btnCreateNotebook" class="btn ubirimi-btn" type="button" value="Create Notebook" />
                    <input id="btnCreateNote" class="btn ubirimi-btn" type="button" value="Create Note" />
                </td>
                <td>
<!--                    <div class="btn-group" >-->
<!--                        <a href="#" class="btn ubirimi-btn dropdown-toggle"-->
<!--                           data-toggle="dropdown">View Options <span class="caret"></span></a>-->

<!--                        <ul class="dropdown-menu pull-left">-->
<!--                            <li><a href="/yongo/issue/printable-list">Snippets</a></li>-->
<!--                            <li><a href="/yongo/issue/printable-list-full-content">List</a></li>-->
<!--                        </ul>-->
<!--                    </div>-->
                </td>
            </tr>
        </table>

        <table id="contentNotesList" width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px solid #DDDDDD;">
            <tr>
                <td width="10%" valign="top" style="border-right: 1px solid #DDDDDD">
                    <div style="height: 100%; overflow: auto">
                        <?php require_once __DIR__ . '/../_notebook_list_section.php' ?>
                    </div>
                </td>
                <td width="350px" valign="top" style="border-right: 1px solid #DDDDDD;">
                    <div style="height: 100%; overflow: auto">
                        <?php require_once __DIR__ . '/../_note_list_section.php' ?>
                    </div>
                </td>
                <td width="75%" valign="top">
                    <?php require_once __DIR__ . '/../_note_section.php' ?>

                </td>
            </tr>
        </table>

        <div id="modalDeleteNote"></div>
        <?php require_once __DIR__ . '/../../../../QuickNotes/Resources/views/_footer.php' ?>
        <input type="hidden" value="<?php echo $notebookId ?>" id="notebook_id" />
        <?php if (isset($noteId)): ?>
            <input type="hidden" value="<?php echo $noteId ?>" id="note_id" />
        <?php endif ?>
        <?php if (isset($tagId)): ?>
            <input type="hidden" value="<?php echo $tagId ?>" id="tag_id" />
        <?php endif ?>
    </div>
</body>