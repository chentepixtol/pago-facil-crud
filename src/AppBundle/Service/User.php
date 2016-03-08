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

    /**
     * @param $email
     * @param $token
     * @return \AppBundle\Entity\User
     */
    public function validateToken($email, $token)
    {
        $user = $this->userRepository->findOneByEmail($email);

        if ($user && $token && $user->getToken() == $token) {
            return $user;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $users = $this->userRepository->findAll();

        return array_map(function($user) {
            $data = $user->toArray();
            unset($data['token']);
            return $data;
        }, $users);
    }

    /**
     * @param $email
     * @param $password
     * @return \AppBundle\Entity\User
     */
    public function create($email, $password)
    {
        $user = new \AppBundle\Entity\User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setToken(null);
        $user->setIsActive(1);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param $id
     * @param $email
     * @param $password
     * @return \AppBundle\Entity\User
     * @throws \Exception
     */
    public function update($id, $email, $password, $status = true)
    {
        $user = $this->userRepository->findOneById($id);

        if (!$user) {
            throw new \Exception(sprintf("El usuario con ID %s no existe", $id));
        }

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setIsActive($status);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\User
     * @throws \Exception
     */
    public function remove($id)
    {
        $user = $this->userRepository->findOneById($id);

        if (!$user) {
            throw new \Exception(sprintf("El usuario con ID %s no existe", $id));
        }

        $user->setIsActive(false);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
