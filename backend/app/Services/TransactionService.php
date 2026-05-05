<?php

namespace App\Services;

use App\Models\Transaction as TransactionModel;
use App\Models\TransactionType as TransactionTypeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use Exception;

class TransactionService {
    private $transactionModel;
    private $transactionTypeModel;
    private $paymentMethodModel;
    
    public function __construct(
        TransactionModel $transactionModel,
        TransactionTypeModel $transactionTypeModel,
        PaymentMethodModel $paymentMethodModel
    ) {
        $this->transactionModel = $transactionModel;
        $this->transactionTypeModel = $transactionTypeModel;
        $this->paymentMethodModel = $paymentMethodModel;
    }
    
    public function getAll($userId) {
        return $this->transactionModel->getAll($userId);
    }
    
    public function getById($id, $userId) {
        return $this->transactionModel->getById($id, $userId);
    }
    
    public function create($data, $userId) {
        if (empty($data['transaction_type_id']) || empty($data['payment_method_id']) || empty($data['amount'])) {
            throw new Exception('Campos obrigatórios: transaction_type_id, payment_method_id, amount');
        }
        
        if ($data['amount'] <= 0) {
            throw new Exception('Valor deve ser maior que zero');
        }
        
        // Validar se o tipo e forma de pagamento pertencem ao usuário
        $type = $this->transactionTypeModel->getById($data['transaction_type_id'], $userId);
        $method = $this->paymentMethodModel->getById($data['payment_method_id'], $userId);
        
        if (!$type || !$method) {
            throw new Exception('Tipo de transação ou forma de pagamento inválido');
        }
        
        $data['user_id'] = $userId;
        $data['transaction_date'] = $data['transaction_date'] ?? date('Y-m-d H:i:s');
        
        return $this->transactionModel->create($data);
    }
    
    public function update($id, $data, $userId) {
        $transaction = $this->transactionModel->getById($id, $userId);
        
        if (!$transaction) {
            throw new Exception('Transação não encontrada');
        }
        
        $this->transactionModel->update($id, $userId, $data);
        return $this->transactionModel->getById($id, $userId);
    }
    
    public function delete($id, $userId) {
        $transaction = $this->transactionModel->getById($id, $userId);
        
        if (!$transaction) {
            throw new Exception('Transação não encontrada');
        }
        
        $this->transactionModel->delete($id, $userId);
    }
    
    public function getDashboardSummary($userId) {
        $totalIncoming = $this->transactionModel->getTotalIncoming($userId);
        $totalOutgoing = $this->transactionModel->getTotalOutgoing($userId);
        $balance = $totalIncoming - $totalOutgoing;
        $count = $this->transactionModel->getCount($userId);
        
        return [
            'total_incoming' => floatval($totalIncoming),
            'total_outgoing' => floatval($totalOutgoing),
            'balance' => floatval($balance),
            'transactions_count' => intval($count)
        ];
    }
}
