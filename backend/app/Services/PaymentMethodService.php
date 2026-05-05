<?php

namespace App\Services;

use App\Models\PaymentMethod as PaymentMethodModel;
use Exception;

class PaymentMethodService {
    private $model;
    
    public function __construct(PaymentMethodModel $model) {
        $this->model = $model;
    }
    
    public function getAll($userId) {
        return $this->model->getAll($userId);
    }
    
    public function getById($id, $userId) {
        return $this->model->getById($id, $userId);
    }
    
    public function create($data, $userId) {
        if (empty($data['name'])) {
            throw new Exception('Campos obrigatórios: name');
        }
        
        $data['user_id'] = $userId;
        return $this->model->create($data);
    }
    
    public function update($id, $data, $userId) {
        $method = $this->model->getById($id, $userId);
        
        if (!$method) {
            throw new Exception('Forma de pagamento não encontrada');
        }
        
        $this->model->update($id, $userId, $data);
        return $this->model->getById($id, $userId);
    }
    
    public function delete($id, $userId) {
        $method = $this->model->getById($id, $userId);
        
        if (!$method) {
            throw new Exception('Forma de pagamento não encontrada');
        }
        
        $this->model->delete($id, $userId);
    }
}
