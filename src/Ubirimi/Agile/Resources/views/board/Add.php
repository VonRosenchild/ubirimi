<?php
    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

<?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>

<div class="pageContent">
    <form name="add_board" action="/agile/board/add" method="post">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > Create Board
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                <td>
                    <input class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
                    <?php if ($emptyName): ?>
                    <div class="error">The name can not be empty.</div>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td valign="top">Description</td>
                <td>
                    <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                </td>
            </tr>
            <tr>
                <td valign="top">Projects</td>
                <td>
                    <select name="project[]" multiple="multiple" size="10" class="inputTextCombo">
                        <?php while ($projects && $project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                        <option value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
                        <?php endwhile ?>
                    </select>
                    <?php if ($noProjectSelected): ?>
                    <div class="error">At least one project must be linked to the board.</div>
                    <?php endif ?>
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
                        <button type="submit" name="confirm_new_board" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Board</button>
                        <a class="btn ubirimi-btn" href="/agile/boards">Cancel</a>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>