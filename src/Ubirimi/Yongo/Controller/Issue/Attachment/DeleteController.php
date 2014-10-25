<?php

namespace Ubirimi\Yongo\Controller\Issue\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;

class DeleteController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session) {
        Util::checkUserIsLoggedInAndRedirect();

        $attachmentId = $request->request->get('att_id');

        $attachment = $this->getRepository(IssueAttachment::class)->getById($attachmentId);

        $this->getRepository(IssueAttachment::class)->deleteById($attachmentId);

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
    }
}