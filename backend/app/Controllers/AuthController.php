<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Utils\Response;
use Exception;

class AuthController {
    private $authService;
    
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    
    public function register() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->authService->register($data);
            Response::success($result, 'Usuário registrado com sucesso', 201);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function login() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['email']) || empty($data['password'])) {
                throw new Exception('Email e senha são obrigatórios');
            }
            
            $result = $this->authService->login($data['email'], $data['password']);
            Response::success($result, 'Login realizado com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 401);
        }
    }
}
