<?php

namespace App\Services;

use App\Models\User;
use App\Utils\Auth;
use App\Utils\Response;
use Exception;

class AuthService {
    private $userModel;
    private $authUtil;
    
    public function __construct(User $userModel, Auth $authUtil) {
        $this->userModel = $userModel;
        $this->authUtil = $authUtil;
    }
    
    public function register($data) {
        // Validações
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new Exception('Campos obrigatórios: name, email, password');
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email inválido');
        }
        
        if (strlen($data['password']) < 6) {
            throw new Exception('Senha deve ter no mínimo 6 caracteres');
        }
        
        if ($this->userModel->exists($data['email'])) {
            throw new Exception('Email já registrado');
        }
        
        // Criar usuário
        $userId = $this->userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->authUtil->hashPassword($data['password'])
        ]);
        
        $user = $this->userModel->findById($userId);
        $token = $this->authUtil->generateToken($user);
        
        return [
            'token' => $token,
            'user' => $user
        ];
    }
    
    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            throw new Exception('Email ou senha incorretos');
        }
        
        if (!$this->authUtil->verifyPassword($password, $user['password'])) {
            throw new Exception('Email ou senha incorretos');
        }
        
        unset($user['password']);
        $token = $this->authUtil->generateToken($user);
        
        return [
            'token' => $token,
            'user' => $user
        ];
    }
}
