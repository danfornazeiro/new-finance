<?php

namespace App\Controllers;

use App\Services\TransactionService;
use App\Utils\Response;
use Exception;

class TransactionController {
    private $service;
    
    public function __construct(TransactionService $service) {
        $this->service = $service;
    }
    
    public function index($userId) {
        try {
            $transactions = $this->service->getAll($userId);
            Response::success($transactions, 'Transações listadas com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function show($id, $userId) {
        try {
            $transaction = $this->service->getById($id, $userId);
            if (!$transaction) {
                Response::error('Transação não encontrada', 404);
            }
            Response::success($transaction, 'Transação recuperada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function store($userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $this->service->create($data, $userId);
            $transaction = $this->service->getById($id, $userId);
            Response::success($transaction, 'Transação criada com sucesso', 201);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function update($id, $userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $transaction = $this->service->update($id, $data, $userId);
            Response::success($transaction, 'Transação atualizada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function destroy($id, $userId) {
        try {
            $this->service->delete($id, $userId);
            Response::success(null, 'Transação deletada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function dashboard($userId) {
        try {
            $summary = $this->service->getDashboardSummary($userId);
            Response::success($summary, 'Resumo do dashboard');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
