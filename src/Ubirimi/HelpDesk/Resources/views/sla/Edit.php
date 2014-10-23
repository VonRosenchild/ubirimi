<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="">SLAs</a> > Update SLA > ' . $SLA['name']) ?>

    <div class="pageContent">
        <form name="update_sla" action="/helpdesk/sla/edit/<?php echo $slaId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input id="name" class="inputText" type="text" value="<?php echo $SLA['name'] ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the SLA can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">Duplicate SLA name. Please choose another name.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge"
                                  name="description"><?php echo $SLA['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <span><b>Start</b></span>
                                    <br />
                                    <span>Begin counting time when</span>
                                </td>
                                <td width="100px"></td>
                                <td>
                                    <span><b>Stop</b></span>
                                    <br />
                                    <span>Finish counting time when</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="vertical-align: middle">
                                    <?php $prefixElementName = 'start'; ?>
                                    <?php require __DIR__ . '/../../views/sla/_startStopConditions.php' ?>
                                </td>
                                <td></td>
                                <td align="left" style="vertical-align: middle">
                                    <?php
                                        $prefixElementName = 'stop';
                                        $availableStatuses->data_seek(0);
                                    ?>
                                    <?php require __DIR__ . '/../../views/sla/_startStopConditions.php' ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <br />
                        <div><b>Goals</b></div>

                        <div>Issues will be checked against this list, top to bottom, and assigned a time target based on the first matching YQL statement.</div>

                        <table class="table table-hover table-condensed" id="slaGoals">
                            <thead>
                                <tr>
                                    <th width="200">Issues (YQL)</th>
                                    <th>Goal</th>
                                    <th>Calendar</th>
                                    <th style="width: 110px">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($goals && $goal = $goals->fetch_array(MYSQLI_ASSOC)): ?>
                                    <?php if ($goal['definition'] == 'all_remaining_issues'): ?>
                                        <?php $allRemainingIssuesDefinitionFound = true ?>
                                    <?php endif ?>
                                    <tr>
                                        <td>
                                            <?php if ($goal['definition'] == 'all_remaining_issues'): ?>
                                                <span>All remaining issues</span>
                                                <input type="hidden" value="all_remaining_issues" name="goal_definition_<?php echo $goal['id'] ?>" />
                                            <?php else: ?>
                                                <textarea class="inputTextAreaLarge goal_autocomplete"
                                                          id="goal_definition_<?php echo $goal['id'] ?>"
                                                          name="goal_definition_<?php echo $goal['id'] ?>"><?php echo $goal['definition'] ?></textarea>
                                            <?php endif ?>
                                        </td>

                                        <td style="vertical-align: top">
                                            <input size="5"
                                                   type="text"
                                                   class="inputText"
                                                   style="width: 110px"
                                                   value="<?php echo $goal['value'] ?>"
                                                   name="goal_value_<?php echo $goal['id'] ?>" /> minutes
                                        </td>
                                        <td style="vertical-align: top">
                                            <?php $slaCalendars->data_seek(0); ?>
                                            <select name="goal_calendar_<?php echo $goal['id'] ?>" class="select2InputSmall">
                                                <?php while ($calendar = $slaCalendars->fetch_array(MYSQLI_ASSOC)): ?>
                                                    <option <?php if ($goal['help_sla_calendar_id'] == $calendar['id']) echo 'selected="selected"' ?> value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </td>
                                        <td style="vertical-align: top">
                                            <button type="button" id="delete_goal_<?php echo $goal['id'] ?>" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                                <?php if (!$allRemainingIssuesDefinitionFound): ?>
                                    <tr>
                                        <td style="vertical-align: top">
                                            All remaining issues
                                            <input type="hidden" value="all_remaining_issues" name="goal_definition_0" />
                                        </td>
                                        <td valign="top">
                                            <input size="5"
                                                   class="inputText"
                                                   type="text"
                                                   style="width: 110px"
                                                   value="" name="goal_value_0" /> minutes
                                        </td>
                                        <td style="vertical-align: top">
                                            <?php $slaCalendars->data_seek(0) ?>
                                            <select name="goal_calendar_0<?php echo $goal['value'] ?>" class="select2InputSmall">
                                                <?php while ($calendar = $slaCalendars->fetch_array(MYSQLI_ASSOC)): ?>
                                                    <option <?php if ($goal['help_sla_calendar_id'] == $calendar['id']) echo 'selected="selected"' ?> value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                        <button type="button" id="btnAddGoal" class="btn ubirimi-btn">Add Another Goal</button>
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
                            <button type="submit" name="confirm_update_sla" class="btn ubirimi-btn"><i class="icon-plus"></i> Update SLA</button>
                            <a class="btn ubirimi-btn" href="<?php echo "/helpdesk/sla/" . $SLA['project_id'] . "/" . $SLA['id'] ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <input type="hidden" value="<?php echo $project['id'] ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>