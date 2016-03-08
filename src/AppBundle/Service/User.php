<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

class User {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, EntityManager $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @param $email
     * @param $password
     * @return array|\AppBundle\Entity\User
     */
    public function login($email, $password)
    {
        $errors = array();
        $user = $this->userRepository->findOneByEmail($email);

        if ($user && $user->getIsActive() && $password == $user->getPassword()) {
            $user->setToken(md5($user->getId() . time() . $user->getEmail()));
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }

        if ($user) {
            if (!$user->getIsActive()) {
                $errors[] = 'El usuario no se encuentra activo';
            }
            if ($password != $user->getPassword()) {
                $errors[] = 'El usuario o password son incorrectos';
            }
        } else {
            $errors[] = 'El usuario no existe ' . $email;
        }

        return $errors;
    }
}
