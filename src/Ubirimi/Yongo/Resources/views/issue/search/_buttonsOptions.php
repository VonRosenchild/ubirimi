<?php
use Ubirimi\Util;

?>
<div class="btn-group" style="float: right; margin-left: 5px;">
    <?php if (Util::checkUserIsLoggedIn() && $projectsForBrowsing): ?>
        <a href="#" class="btn ubirimi-btn dropdown-toggle <?php if (!$hasGlobalBulkPermission): ?>disabled<?php endif ?>" data-toggle="dropdown">
            Options <span class="caret"></span>
        </a>

        <ul class="dropdown-menu pull-right">
            <li><a href="/yongo/issue/bulk-choose?<?php echo urldecode($query) ?>">Bulk Change</a></li>
        </ul>
    <?php endif ?>

</div>

<div class="btn-group" style="float: right; margin-right: 0px;">
    <a href="#" class="btn ubirimi-btn dropdown-toggle <?php if (!($projectsForBrowsing && $query)) echo 'disabled'; ?>" data-toggle="dropdown">Views <span class="caret"></span></a>
    <?php if ($projectsForBrowsing && $query): ?>
        <ul class="dropdown-menu pull-right">
            <li><a href="/yongo/issue/printable-list?<?php echo urldecode($query); ?>">Printable</a></li>
            <li><a href="/yongo/issue/printable-list-full-content?<?php echo urldecode($query); ?>">Full Content</a></li>
        </ul>
    <?php endif ?>
</div>
<?php if (Util::checkUserIsLoggedIn()): ?>
    <div class="btn-group" style="float: right; margin-right: 0px;" id="btnIssueSearchColumns">
        <a href="#" class="btn ubirimi-btn dropdown-toggle">Columns <span class="caret"></span></a>
    </div>
<?php endif ?>