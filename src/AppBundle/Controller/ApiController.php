<?php

namespace AppBundle\Controller;

use AppBundle\Service\BaseController;
use AppBundle\Service\Auth;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    use BaseController;
    use Auth;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $user = $this->serviceUser->login($request->request->get('email'), $request->request->get('password'));
        if ($user instanceof \AppBundle\Entity\User) {
            return $this->jsonResponse([
                'result' => true,
                'model' => $user->toArray(),
            ]);
        } else {
            return $this->jsonResponse([
                'result' => false,
                'errors' => $user
            ], '500');
        }
    }

}
