<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

    Util::checkUserIsLoggedInAndRedirect();

    $attachmentId = $_POST['att_id'];

    $attachment = IssueAttachment::getById($attachmentId);

    IssueAttachment::deleteById($attachmentId);

    $pathToAttachment = UbirimiContainer::get()['asset.root_folder'] . UbirimiContainer::get()['asset.yongo_issue_attachments'];

    unlink($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/' . $attachment['name']);
    if (Util::isImage(Util::getExtension($attachment['name']))) {
        unlink($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/thumbs/' . $attachment['name']);
        Util::deleteDir($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id'] . '/thumbs');
    }

    Util::deleteDir($pathToAttachment . $attachment['issue_id'] . '/' . $attachment['id']);

    // delete the issue folder if necessary
    if (2 == count(scandir($pathToAttachment . $attachment['issue_id']))) {
        Util::deleteDir($pathToAttachment . $attachment['issue_id']);
    }