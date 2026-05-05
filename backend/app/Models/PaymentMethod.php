<?php

namespace App\Models;

use App\Utils\Database;

class PaymentMethod {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function getAll($userId) {
        $sql = "SELECT * FROM payment_methods WHERE user_id = ? ORDER BY name ASC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getById($id, $userId) {
        $sql = "SELECT * FROM payment_methods WHERE id = ? AND user_id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id, $userId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO payment_methods (name, user_id, created_at) VALUES (?, ?, NOW())";
        $this->db->execute($sql, [
            $data['name'],
            $data['user_id']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $userId, $data) {
        $sql = "UPDATE payment_methods SET name = ? WHERE id = ? AND user_id = ?";
        $this->db->execute($sql, [
            $data['name'],
            $id,
            $userId
        ]);
    }
    
    public function delete($id, $userId) {
        $sql = "DELETE FROM payment_methods WHERE id = ? AND user_id = ?";
        $this->db->execute($sql, [$id, $userId]);
    }
}
