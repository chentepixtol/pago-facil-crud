<?php

namespace AppBundle\Controller;

use AppBundle\Service\Auth;
use AppBundle\Service\BaseController;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    use BaseController;
    use Auth;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }

        try {
            $newUser = $this->serviceUser->create($request->request->get('email'), $request->request->get('password'));

            return $this->jsonResponse([
                'result' => true,
                'model' => $newUser->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }
        try {
            $userEdited = $this->serviceUser->update(
                $request->request->get('id'),
                $request->request->get('email'),
                $request->request->get('password')
            );

            return $this->jsonResponse([
                'result' => true,
                'model' => $userEdited->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }

        try {
            $userRemoved = $this->serviceUser->remove($request->request->get('id'));

            return $this->jsonResponse([
                'result' => true,
                'model' => $userRemoved->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
