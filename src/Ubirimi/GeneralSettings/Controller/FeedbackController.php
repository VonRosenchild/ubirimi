<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

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