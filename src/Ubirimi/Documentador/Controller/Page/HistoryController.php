<?php



use Ubirimi\SystemProduct;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();
$menuSelectedCategory = 'documentator';

$session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
$clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

$entityId = $_GET['id'];
$page = Entity::getById($entityId, $loggedInUserId);

$spaceId = $page['space_id'];
$space = Space::getById($spaceId);
$revisions = Entity::getRevisionsByPageId($entityId);

$revisionCount = ($revisions) ? $revisions->num_rows + 1 : 1;

$sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / History';

require_once __DIR__ . '/../../Resources/views/page/History.php';