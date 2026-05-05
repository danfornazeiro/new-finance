<?php

namespace App\Utils;

use Exception;

class JWT {
    private $secret;
    private $algorithm;
    private $expiration;
    
    public function __construct($config) {
        $this->secret = $config['secret'];
        $this->algorithm = $config['algorithm'];
        $this->expiration = $config['expiration'];
    }
    
    public function encode($payload) {
        $payload['iat'] = time();
        $payload['exp'] = time() + $this->expiration;
        
        $header = $this->base64UrlEncode(json_encode([
            'alg' => $this->algorithm,
            'typ' => 'JWT'
        ]));
        
        $payload = $this->base64UrlEncode(json_encode($payload));
        
        $signature = $this->base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $this->secret, true)
        );
        
        return "$header.$payload.$signature";
    }
    
    public function decode($token) {
        try {
            $parts = explode('.', $token);
            
            if (count($parts) !== 3) {
                throw new Exception('Token inválido');
            }
            
            $payload = json_decode($this->base64UrlDecode($parts[1]), true);
            
            if (!$payload) {
                throw new Exception('Token inválido');
            }
            
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new Exception('Token expirado');
            }
            
            $signature = $this->base64UrlEncode(
                hash_hmac('sha256', "$parts[0].$parts[1]", $this->secret, true)
            );
            
            if ($signature !== $parts[2]) {
                throw new Exception('Assinatura do token inválida');
            }
            
            return $payload;
        } catch (Exception $e) {
            throw new Exception('Erro ao decodificar token: ' . $e->getMessage());
        }
    }
    
    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', strlen($data) % 4));
    }
}
