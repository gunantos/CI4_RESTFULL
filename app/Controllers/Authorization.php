<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;

class Authorization extends ResourceController
{
    use ResponseTrait;

    public function index() {
        return Services::response()->setJSON(['status'=> 401,'error' => 'Combination email and password wrong'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }

    public function create()
    {
        $userModel = new UserModel();
        $userdata = $userModel->where("email", $this->request->getVar("email"))->first();
        if (!empty($userdata)) {
            if (password_verify($this->request->getVar("password"), $userdata['password'])) {
                try {
                    helper('jwt');
                    $token = getSignedJWTForUser($userdata->email);
                    $response = [
                        'status' => 200,
                        'error' => NULL,
                        'token' => $token
                    ];
                    return $this->respondCreated($response);
                } catch (Exception $e) {
                    return Services::response()->setJSON(['status'=> 401,'error' => $e->getMessage()])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
                }
            }
        }
        return Services::response()->setJSON(['status'=> 401,'error' => 'Combination email and password wrong'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }
}