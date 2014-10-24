<?php

namespace Ubirimi\Api\Controller\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\Issue;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $id = $request->get('id');
        $issue = UbirimiContainer::getRepository(Issue::class)->getById($id);

        if (null === $issue) {
            throw new NotFoundHttpException(sprintf('Issue [%d] not found', $id));
        }

        return new JsonResponse($issue);
    }
}
