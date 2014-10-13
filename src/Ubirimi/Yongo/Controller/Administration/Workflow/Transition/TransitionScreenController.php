<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $stepIdFrom = $_GET['id_from'];
    $stepIdTo = $_GET['id_to'];
    $workflowId = $_GET['workflow_id'];

    $workflowMetadata = $this->getRepository('yongo.workflow.workflow')->getMetaDataById($workflowId);

    $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);
    $transitionName = $workflowData['transition_name'];
    $screens = Screen::getAll($clientId);
    $initialStep = $this->getRepository('yongo.workflow.workflow')->getInitialStep($workflowId);

?>
<div>Transition name: <input class="inputText" type="text" value="<?php echo $transitionName ?>" id="transition_name_modal" /></div>
<div style="height: 4px"></div>
<?php if ($initialStep['id'] != $stepIdFrom): ?>
    <div>
        <span>Set screen for transition:</span>
        <select name="screen_modal" id="screens_modal" class="inputTextCombo">
            <option <?php if (!$workflowData['screen_id']) echo 'selected="selected"' ?> value="-1">No screen</option>
            <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                <option <?php if ($screen['id'] == $workflowData['screen_id']) echo 'selected="selected"' ?> value="<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></option>
            <?php endwhile ?>
        </select>
    <div>
<?php endif ?>
<input type="hidden" value="<?php echo $stepIdFrom ?>" id="id_from_modal" />
<input type="hidden" value="<?php echo $stepIdTo ?>" id="id_to_modal" />
<input type="hidden" value="<?php echo $workflowId ?>" id="project_workflow_id_modal" />