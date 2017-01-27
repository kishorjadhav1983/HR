<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
namespace Orangehrm\Rest\Api\Pim;

use Orangehrm\Rest\Api\Pim\Entity\Employee;
use Orangehrm\Rest\Api\Pim\Entity\EmployeeDependant;
use Orangehrm\Rest\http\RequestParams;


class EmployeeService {

    protected $request;
    protected $employeeService;
    protected $requestParams;

    protected function getEmployeeService() {

        if ($this->employeeService != null) {
            return $this->employeeService;
        } else {
            return new \EmployeeService();
        }
    }

    public function setEmployeeService($employeeService){
        $this->employeeService = $employeeService;
    }
    /**
     * @return mixed
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

     /**
     * Search Employee API call
     *
     * @param $request
     * @return array
     */
    public function getEmployeeList($requestParams) {

        $firstName = $requestParams->getQueryParam('firstName');
        $parameterHolder = new \EmployeeSearchParameterHolder();
        $filters = array('firstName' => $firstName);
        $parameterHolder->setFilters($filters);
        $parameterHolder->setReturnType(\EmployeeSearchParameterHolder::RETURN_TYPE_OBJECT);
        $employees = $this->getEmployeeService()->searchEmployees($parameterHolder);

        foreach ($employees as $employee) {

            $emp = new Employee($employee->getFirstName(), $employee->getMiddleName(), $employee->getLastName(), 25);
            $responseArray[] = $emp->toArray();
        }

        return $responseArray;

    }

    /**
     * Getting employee dependants API call
     *
     * @param $request
     * @return array
     */
    public function getEmployeeDependants($requestParams) {

        $empId = $requestParams->getQueryParam('id');
        $dependants = $this->getEmployeeService()->getEmployeeDependents($empId);
        foreach ($dependants as $dependant) {

            $empDependant = new EmployeeDependant($dependant->getName(), $dependant->getRelationship(), $dependant->getDateOfBirth());
            $responseArray[] = $empDependant->toArray();
        }
        return $responseArray;
    }

    /**
     * Getting employee dependants API call
     *
     * @param $request
     * @return array
     */
    public function getEmployeeDetails($requestParams) {

        $empId = $requestParams->getQueryParam('id');
        $employee = $this->getEmployeeService()->getEmployee($empId);
        $emp = new Employee($employee->getFirstName(), $employee->getMiddleName(), $employee->getLastName(), 25);
        $emp->buildEmployee($employee);
        $responseArray[] = $emp->toArray();

        return $responseArray;

    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

}