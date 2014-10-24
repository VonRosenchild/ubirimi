<?php

namespace Ubirimi\Api\Controller\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;


class PostController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $timeTrackingDefaultUnit = $this->getRepository(UbirimiClient::class)->getYongoSetting(
            $request->get('api_client_id'),
            'time_tracking_default_unit'
        );

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($request->get('api_client_id'));

        $issue = UbirimiContainer::get()['issue']->save(
            array('id' => $request->get('projectId')),
            array(
                'resolution' => $request->get('resolution'),
                'priority' => $request->get('priority'),
                'type' => $request->get('type'),
                'assignee' => $request->get('assignee'),
                'summary' => $request->get('summary'),
                'description' => $request->get('description'),
                'environment' => $request->get('environment'),
                'reporter' => $request->get('api_user_id'),
                'due_date' => $request->get('due_date')
            ),
            null,
            $timeTrackingDefaultUnit,
            $request->get('projectId'),
            array(),
            array(),
            $clientSettings,
            $request->get('api_user_id'),
            $request->get('api_client_id')
        );

        return new JsonResponse($issue);
    }
}
