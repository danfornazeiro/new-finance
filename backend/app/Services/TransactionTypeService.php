<?php

namespace App\Services;

use App\Models\TransactionType as TransactionTypeModel;
use Exception;

class TransactionTypeService {
    private $model;
    
    public function __construct(TransactionTypeModel $model) {
        $this->model = $model;
    }
    
    public function getAll($userId) {
        return $this->model->getAll($userId);
    }
    
    public function getById($id, $userId) {
        return $this->model->getById($id, $userId);
    }
    
    public function create($data, $userId) {
        if (empty($data['name']) || empty($data['type'])) {
            throw new Exception('Campos obrigatórios: name, type');
        }
        
        if (!in_array($data['type'], ['incoming', 'outgoing'])) {
            throw new Exception('Tipo deve ser "incoming" ou "outgoing"');
        }
        
        $data['user_id'] = $userId;
        return $this->model->create($data);
    }
    
    public function update($id, $data, $userId) {
        $type = $this->model->getById($id, $userId);
        
        if (!$type) {
            throw new Exception('Tipo de transação não encontrado');
        }
        
        $this->model->update($id, $userId, $data);
        return $this->model->getById($id, $userId);
    }
    
    public function delete($id, $userId) {
        $type = $this->model->getById($id, $userId);
        
        if (!$type) {
            throw new Exception('Tipo de transação não encontrado');
        }
        
        $this->model->delete($id, $userId);
    }
}
