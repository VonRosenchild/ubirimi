<?php
use Ubirimi\LinkHelper;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();

$projectId = $request->get('id');

?>
<table cellpadding="8px">
    <tr>
        <td colspan="3"><div class="headerPageText">Project predefined filters</div></td>
    </tr>
    <tr>
        <td><?php echo LinkHelper::getYongoIssueListPageLink('All Issues', array('page' => 1, 'class' => ' ', 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc')); ?></td>
        <td><?php echo LinkHelper::getYongoIssueListPageLink('All Open Issues', array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'sort_order' => 'desc', 'resolution' => '-2', 'project' => $projectId)); ?></td>
        <td><?php echo LinkHelper::getYongoIssueListPageLink('My Open Issues', array('page' => 1, 'assignee' => $loggedInUserId, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'sort_order' => 'desc', 'resolution' => '-2', 'project' => $projectId)); ?></td>
    </tr>
</table>