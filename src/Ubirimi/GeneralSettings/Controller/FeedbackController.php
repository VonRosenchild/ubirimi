<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\UbirimiEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FeedbackController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $like = $request->request->get('like');
        $improve = $request->request->get('improve');
        $newFeatures = $request->request->get('new_features');
        $experience = $request->request->get('experience');

        $userData = $this->getRepository(UbirimiUser::class)->getById($loggedInUserId);

        $event = new UbirimiEvent(
            array(
                'userData' => $userData,
                'like' => $like,
                'improve' => $improve,
                'newFeatures' => $newFeatures,
                'experience' => $experience
            )
        );

        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::FEEDBACK, $event);

        return new Response('');
    }

}