<?php

namespace AppBundle\Service;

trait Auth
{

    /**
     * @var User
     */
    protected $serviceUser;

    /**
     * @param User $serviceUser
     */
    public function setServiceUser(User $serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    /**
     * @param $token
     * @return bool
     */
    public function isTokenValid($user, $token)
    {
        return $this->serviceUser->validateToken($user, $token);
    }
}