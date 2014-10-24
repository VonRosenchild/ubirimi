<?php

namespace Ubirimi\Api\Service;

use Symfony\Component\HttpFoundation\Request;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Service\PasswordService;

class BasicAuthenticationService
{
    /**
     * @var \Ubirimi\Service\PasswordService;
     */
    private $passwordService;

    public function setPasswordService(PasswordService $service)
    {
        $this->passwordService = $service;
    }

    public function auth(Request $request)
    {
        $decodedHeader = base64_decode(str_replace('Basic ', '', $request->headers->get('Authorization')));

        list($clientDomain, $username) = explode('#', substr($decodedHeader, 0, strpos($decodedHeader, ':')));
        $password = substr($decodedHeader, strpos($decodedHeader, ':') + 1);

        $user = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientDomain($username, $clientDomain);

        if (null === $user) {
            throw new \Exception(sprintf('Api Auth Failed. User [%s] not found', $username));
        }

        if (false === $this->passwordService->check($password, $user['password'])) {
            throw new \Exception(sprintf('Api Auth Failed. Wrong password for user [%s]', $username));
        }

        $request->attributes->set('api_client_id', $user['client_id']);
        $request->attributes->set('api_client_domain', $clientDomain);
        $request->attributes->set('api_username', $username);
        $request->attributes->set('api_user_id', $user['id']);
    }
}
