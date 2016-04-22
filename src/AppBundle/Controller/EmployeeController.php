<?php

namespace AppBundle\Controller;

use AppBundle\Service\Auth;
use AppBundle\Service\BaseController;
use AppBundle\Service\Employee;
use Symfony\Component\HttpFoundation\Request;

class EmployeeController
{
    use BaseController;
    use Auth;

    /**
     * @var Employee
     */
    protected $serviceEmployee;

    /**
     * @param Employee $serviceEmployee
     */
    public function setServiceEmployee(Employee $serviceEmployee)
    {
        $this->serviceEmployee = $serviceEmployee;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findAction(Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }

        try {
            $users = $this->serviceEmployee->getAll();
            return $this->jsonResponse([
                'result' => true,
                'collection' => $users,
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
    public function createAction(Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }

        try {
            $newEmployee = $this->serviceEmployee->create(
                $request->request->get('first_name'),
                $request->request->get('last_name'),
                $request->request->get('surname'),
                $request->request->get('birthdate'),
                $request->request->get('salary')
            );

            return $this->jsonResponse([
                'result' => true,
                'model' => $newEmployee->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id, Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }
        try {
            $employee = $this->serviceEmployee->update(
                $id,
                $request->request->get('first_name'),
                $request->request->get('last_name'),
                $request->request->get('surname'),
                $request->request->get('birthdate'),
                $request->request->get('salary')
            );

            return $this->jsonResponse([
                'result' => true,
                'model' => $employee->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, Request $request)
    {
        if (!$this->isTokenValid($request->query->get('user'), $request->query->get('token'))) {
            return $this->jsonResponse([
                'result' => false,
                'message' => 'Token invalido',
            ], 403);
        }

        try {
            $employee = $this->serviceEmployee->remove($id);

            return $this->jsonResponse([
                'result' => true,
                'model' => $employee->toArray(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
