<?php

namespace App\Utils;

class Auth {
    private $jwt;
    
    public function __construct($jwt) {
        $this->jwt = $jwt;
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function generateToken($user) {
        return $this->jwt->encode([
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name']
        ]);
    }
    
    public function getTokenFromRequest() {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    public function validateToken($token) {
        return $this->jwt->decode($token);
    }
}
