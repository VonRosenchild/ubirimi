<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="">SLA</a> > Create SLA') ?>

    <div class="pageContent">
        <form name="add_sla" action="/helpdesk/sla/add/<?php echo $projectId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input id="name" class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
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
                                  name="description"><?php if (isset($description)) echo $description ?></textarea>
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
                                <td align="left">
                                    <?php $prefixElementName = 'start'; ?>
                                    <?php require __DIR__ . '/../../views/sla/_startStopConditions.php' ?>
                                </td>
                                <td></td>
                                <td align="left">
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
                                    <th>Issues (YQL)</th>
                                    <th>Goal</th>
                                    <th>Calendar</th>
                                    <th width="110px;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align: top">
                                        <textarea class="inputTextAreaLarge goal_autocomplete"
                                                  id="goal_definition_1"
                                                  name="goal_definition_1"></textarea>
                                    </td>
                                    <td style="vertical-align: top">
                                        <input size="5"
                                               type="text"
                                               value=""
                                               style="width: 110px"
                                               class="inputText"
                                               name="goal_value_1" /> minutes
                                    </td>
                                    <td style="vertical-align: top">
                                        <select name="goal_calendar_1" class="select2InputSmall">
                                            <?php while ($calendar = $slaCalendars->fetch_array(MYSQLI_ASSOC)): ?>
                                                <option value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
                                            <?php endwhile ?>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top">
                                        All remaining issues
                                        <input type="hidden" 
                                               value=""
                                               name="goal_definition_0" />
                                    </td>
                                    <td style="vertical-align: top">
                                        <input size="5" type="text"
                                               style="width: 110px"
                                               value=""
                                               class="inputText"
                                               name="goal_value_0" /> minutes
                                    </td>
                                    <?php $slaCalendars->data_seek(0) ?>
                                    <td style="vertical-align: top">
                                        <select name="goal_calendar_0" class="select2InputSmall">
                                            <?php while ($calendar = $slaCalendars->fetch_array(MYSQLI_ASSOC)): ?>
                                                <option value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
                                            <?php endwhile ?>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
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
                            <button type="submit" name="confirm_new_sla" class="btn ubirimi-btn"><i class="icon-plus"></i> Create SLA</button>
                            <a class="btn ubirimi-btn" href="<?php if (isset($slaSelected)) echo "/helpdesk/sla/" . $projectId . "/" . $slaSelected['id']; else echo '/helpdesk/sla/' . $projectId . '/-1'; ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>