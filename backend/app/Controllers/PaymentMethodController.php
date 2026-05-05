<?php

namespace App\Controllers;

use App\Services\PaymentMethodService;
use App\Utils\Response;
use Exception;

class PaymentMethodController {
    private $service;
    
    public function __construct(PaymentMethodService $service) {
        $this->service = $service;
    }
    
    public function index($userId) {
        try {
            $methods = $this->service->getAll($userId);
            Response::success($methods, 'Formas de pagamento listadas');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function show($id, $userId) {
        try {
            $method = $this->service->getById($id, $userId);
            if (!$method) {
                Response::error('Forma de pagamento não encontrada', 404);
            }
            Response::success($method, 'Forma recuperada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function store($userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $this->service->create($data, $userId);
            $method = $this->service->getById($id, $userId);
            Response::success($method, 'Forma criada com sucesso', 201);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function update($id, $userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $method = $this->service->update($id, $data, $userId);
            Response::success($method, 'Forma atualizada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function destroy($id, $userId) {
        try {
            $this->service->delete($id, $userId);
            Response::success(null, 'Forma deletada com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
