<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Event\UbirimiEvent;
    use Ubirimi\Event\UbirimiEvents;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $like = $_POST['like'];
    $improve = $_POST['improve'];
    $newFeatures = $_POST['new_features'];
    $experience = $_POST['experience'];

    $userData = User::getById($loggedInUserId);

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