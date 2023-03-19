<?php

namespace QuizApp\Controller\Api;

use QuizApp\Model\UserModel;

class UserController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     * @throws \JsonException
     */
    public function listAction(): void
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) === 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers, JSON_THROW_ON_ERROR);
            } catch (\Exception $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc), JSON_THROW_ON_ERROR),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
