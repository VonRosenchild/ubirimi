<?php if ($hasViewVotersAndWatchersPermission): ?>
    <?php if ($hasManageWatchersPermission): ?>
        <table width="100%">
            <thead>
            <tr>
                <th align="left">
                    <span>Add Watcher</span>
                </th>
                <th valign="top" align="right">
                    <img style="cursor: pointer" id="closeWatchedDialog" width="20px" src="/img/close.png" />
                </th>
            </tr>
            </thead>

            <tr>
                <td colspan="2">
                    <select style="width: 100%" name="user_to_watch[]" id="user_to_watch" class="inputTextCombo select2Input" multiple="multiple">
                        <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php if (!in_array($user['id'], $watcherArray)): ?>
                                <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                            <?php endif ?>
                        <?php endwhile ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php endif ?>
    <?php if (!$watchers): ?>
        <div>There are no watchers.</div>
    <?php else: ?>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th style="padding-left: 0" colspan="2"><span>Current watchers</span></th>
            </tr>
            </thead>
        </table>
        <div style="max-height: 150px; overflow-y: auto;">
            <table class="table table-hover table-condensed">
                <tbody>
                <?php while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td style="padding-left: 0">
                            <?php echo $watcher['first_name'] . ' ' . $watcher['last_name'] ?>
                        </td>
                        <td style="text-align: right" align="right">
                            <?php if ($hasManageWatchersPermission): ?>
                                <img style="cursor: pointer" id="remove_watcher_<?php echo $watcher['id'] ?>" width="20px" src="/img/close.png" />
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
    <?php if ($hasManageWatchersPermission): ?>
        <div align="right">
            <input class="btn ubirimi-btn" id="add_watcher" type="button" value="Add Watchers" />
        </div>
    <?php endif ?>
<?php endif ?>