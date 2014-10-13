<?php

namespace Ubirimi\Api\Controller\Project;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Project\Project;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $code = $request->get('code');

        $project = $this->getRepository('yongo.project.project')->getByCode($code, null, $request->get('api_client_id'));

        if (false === $project) {
            throw new NotFoundHttpException(sprintf('Project [%s] not found', $code));
        }

        return new JsonResponse($project);
    }
}
