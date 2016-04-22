<?php

namespace AppBundle\Service;

use AppBundle\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;

class Employee {

    /**
     * @var EmployeeRepository
     */
    protected $employeeRepository;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EmployeeRepository $userRepository
     */
    public function __construct(EmployeeRepository $userRepository, EntityManager $em)
    {
        $this->employeeRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $users = $this->employeeRepository->findAll();

        return array_map(function($user) {
            $data = $user->toArray();
            unset($data['token']);
            return $data;
        }, $users);
    }


    /**
     * @param $firstName
     * @param $lastName
     * @param $surname
     * @param $birthdate
     * @param $salary
     * @return \AppBundle\Entity\Employee
     */
    public function create($firstName, $lastName, $surname, $birthdate, $salary)
    {
        $employee = new \AppBundle\Entity\Employee();
        $employee->setFirstName($firstName);
        $employee->setLastName($lastName);
        $employee->setBirthdate(\DateTime::createFromFormat('d-m-Y', $birthdate));
        $employee->setSurname($surname);
        $employee->setSalary($salary);
        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $surname
     * @param $birthdate
     * @param $salary
     * @return \AppBundle\Entity\Employee
     * @throws \Exception
     */
    public function update($id, $firstName, $lastName, $surname, $birthdate, $salary)
    {
        $employee = $this->employeeRepository->findOneById($id);

        if (!$employee) {
            throw new \Exception(sprintf("El emplado con ID %s no existe", $id));
        }

        $employee->setFirstName($firstName);
        $employee->setLastName($lastName);
        $employee->setBirthdate(\DateTime::createFromFormat('d-m-Y', $birthdate));
        $employee->setSurname($surname);
        $employee->setSalary($salary);

        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\Employee
     * @throws \Exception
     */
    public function remove($id)
    {
        $employee = $this->employeeRepository->findOneById($id);

        if (!$employee) {
            throw new \Exception(sprintf("El usuario con ID %s no existe", $id));
        }

        $this->em->remove($employee);
        $this->em->flush();

        return $employee;
    }
}
