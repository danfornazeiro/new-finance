<?php

namespace App\Models;

use App\Utils\Database;

class Transaction {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function getAll($userId) {
        $sql = "SELECT t.*, tt.name as transaction_type_name, tt.type, pm.name as payment_method_name 
                FROM transactions t
                JOIN transaction_types tt ON t.transaction_type_id = tt.id
                JOIN payment_methods pm ON t.payment_method_id = pm.id
                WHERE t.user_id = ? 
                ORDER BY t.transaction_date DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getById($id, $userId) {
        $sql = "SELECT t.*, tt.name as transaction_type_name, tt.type, pm.name as payment_method_name 
                FROM transactions t
                JOIN transaction_types tt ON t.transaction_type_id = tt.id
                JOIN payment_methods pm ON t.payment_method_id = pm.id
                WHERE t.id = ? AND t.user_id = ? LIMIT 1";
        return $this->db->fetch($sql, [$id, $userId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO transactions (user_id, transaction_type_id, payment_method_id, amount, description, transaction_date, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->execute($sql, [
            $data['user_id'],
            $data['transaction_type_id'],
            $data['payment_method_id'],
            $data['amount'],
            $data['description'],
            $data['transaction_date']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $userId, $data) {
        $sql = "UPDATE transactions SET transaction_type_id = ?, payment_method_id = ?, amount = ?, description = ?, transaction_date = ?, updated_at = NOW() 
                WHERE id = ? AND user_id = ?";
        
        $this->db->execute($sql, [
            $data['transaction_type_id'],
            $data['payment_method_id'],
            $data['amount'],
            $data['description'],
            $data['transaction_date'],
            $id,
            $userId
        ]);
    }
    
    public function delete($id, $userId) {
        $sql = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
        $this->db->execute($sql, [$id, $userId]);
    }
    
    public function getTotalIncoming($userId) {
        $sql = "SELECT COALESCE(SUM(t.amount), 0) as total 
                FROM transactions t
                JOIN transaction_types tt ON t.transaction_type_id = tt.id
                WHERE t.user_id = ? AND tt.type = 'incoming'";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }
    
    public function getTotalOutgoing($userId) {
        $sql = "SELECT COALESCE(SUM(t.amount), 0) as total 
                FROM transactions t
                JOIN transaction_types tt ON t.transaction_type_id = tt.id
                WHERE t.user_id = ? AND tt.type = 'outgoing'";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }
    
    public function getCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM transactions WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['count'] ?? 0;
    }
}
