<?php

namespace AppBundle\Controller;

use AppBundle\Service\BaseController;

class UserController
{
    use BaseController;

    public function createAction()
    {
        return $this->jsonResponse(['message' => 'user created']);
    }

    public function updateAction()
    {
        return $this->jsonResponse(['message' => 'user updated']);
    }

    public function deleteAction()
    {
        return $this->jsonResponse(['message' => 'user removed']);
    }

}
