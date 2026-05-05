<?php

namespace App\Models;

use App\Utils\Database;

class User {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }
    
    public function findById($id) {
        $sql = "SELECT id, name, email, created_at FROM users WHERE id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
        $this->db->execute($sql, [
            $data['name'],
            $data['email'],
            $data['password']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function exists($email) {
        return $this->findByEmail($email) !== false;
    }
}
