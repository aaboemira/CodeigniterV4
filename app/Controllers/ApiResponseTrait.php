<?php

namespace App\Controllers;

trait ApiResponseTrait
{
    protected function respondSuccess($data, $code = 200)
    {
        return $this->response->setStatusCode($code)->setJSON($data);
    }

    protected function failUnauthorized($message = 'Unauthorized')
    {
        return $this->response->setStatusCode(401)->setJSON(['error' => $message]);
    }

    protected function failNotFound($message = 'Not Found')
    {
        return $this->response->setStatusCode(404)->setJSON(['error' => $message]);
    }

    protected function failServerError($message = 'Server Error')
    {
        return $this->response->setStatusCode(500)->setJSON(['error' => $message]);
    }

    protected function fail($message, $code = 400)
    {
        return $this->response->setStatusCode($code)->setJSON(['error' => $message]);
    }
}
