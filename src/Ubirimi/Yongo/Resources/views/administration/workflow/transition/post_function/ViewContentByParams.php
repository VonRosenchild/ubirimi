<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

if ($postFunctions): ?>
    <?php $index = 1 ?>
    <div>Already added post functions: </div>
    <?php while ($postFunction = $postFunctions->fetch_array(MYSQLI_ASSOC)): ?>
        <div>
            <span><?php echo $index++ . '. ' . UbirimiContainer::get()['repository']->get(WorkflowFunction::class)->getFunctionDescription($postFunction); ?></span>
        </div>
    <?php endwhile ?>
    <hr size="1" />
<?php endif ?>
<div>
    <?php if ($postFunctions): ?>
        <span>Add another post function</span>
    <?php else: ?>
        <span>Select function</span>
    <?php endif ?>
    <select name="post_function" id="post_function_select" class="inputTextCombo">
        <option value="-1">None</option>
        <?php while ($postFunction = $allPostFunctions->fetch_array(MYSQLI_ASSOC)): ?>
            <?php if ($postFunction['user_addable_flag']): ?>
                <option value="<?php echo $postFunction['id'] ?>"><?php echo $postFunction['name'] ?></option>
            <?php endif ?>
        <?php endwhile ?>
    </select>
</div>
<div id="content_post_function"></div>