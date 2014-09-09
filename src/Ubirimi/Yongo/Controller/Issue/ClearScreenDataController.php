<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;

    $attIdsSession = $session->has('added_attachments_in_screen') ? $session->get('added_attachments_in_screen') : null;
    if ($attIdsSession) {
        for ($i = 0; $i < count($attIdsSession); $i++) {

            $attachmentId = $attIdsSession[$i];

            IssueAttachment::deleteById($attachmentId);

            if ($issueId) {
                Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issueId . '/' . $attachmentId);
            }
        }

        $session->remove('added_attachments_in_screen');
    }

    if (file_exists(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . 'user_' . $loggedInUserId)) {
        Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . 'user_' . $loggedInUserId);
    }