<?php

namespace App\Controllers;

use App\Services\TransactionTypeService;
use App\Utils\Response;
use Exception;

class TransactionTypeController {
    private $service;
    
    public function __construct(TransactionTypeService $service) {
        $this->service = $service;
    }
    
    public function index($userId) {
        try {
            $types = $this->service->getAll($userId);
            Response::success($types, 'Tipos de transação listados');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function show($id, $userId) {
        try {
            $type = $this->service->getById($id, $userId);
            if (!$type) {
                Response::error('Tipo não encontrado', 404);
            }
            Response::success($type, 'Tipo recuperado com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function store($userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $this->service->create($data, $userId);
            $type = $this->service->getById($id, $userId);
            Response::success($type, 'Tipo criado com sucesso', 201);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function update($id, $userId) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $type = $this->service->update($id, $data, $userId);
            Response::success($type, 'Tipo atualizado com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    
    public function destroy($id, $userId) {
        try {
            $this->service->delete($id, $userId);
            Response::success(null, 'Tipo deletado com sucesso');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
