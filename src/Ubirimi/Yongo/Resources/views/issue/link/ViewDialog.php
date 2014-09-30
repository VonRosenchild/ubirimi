<?php if ($linkPossible): ?>
    Select a Yongo issue to link this issue to:
    <div class="requiredModalField" id="linkWarning"></div>
    <table width="100%" class="modal-table">
        <tr>
            <td width="100px">This issue</td>
            <td>
                <select id="link_type" class="select2InputSmall">
                    <?php while ($linkType = $types->fetch_array(MYSQLI_ASSOC)): ?>
                        <option value="<?php echo $linkType['id'] ?>_outward"><?php echo $linkType['outward_description'] ?></option>
                        <option value="<?php echo $linkType['id'] ?>_inward"><?php echo $linkType['inward_description'] ?></option>
                    <?php endwhile ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top">Issue</td>
            <td>
                <select data-placeholder="Select an issue..." id="link_child_issue" name="link_child_issue" multiple="multiple" class="select2Input" style="width: 400px;">
                    <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                        <?php if ($issue['id'] != $issueId): ?>
                            <option value="<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . '-' . $issue['nr']; ?></option>
                        <?php endif ?>
                    <?php endwhile ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top">Comment</td>
            <td>
                <textarea class="inputTextAreaLarge" style="height: 200px;" id="link_comment"></textarea>
            </td>
        </tr>
    </table>
    <br />
<?php else: ?>
    <div>There are no link types defined for this Yongo instance.</div>
<?php endif ?>