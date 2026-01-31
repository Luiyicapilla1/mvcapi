<?php

namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

class ApiCar extends Controlador
{
    public function __construct(){
        $this->modelo = $this->modelo('car');
    }

    private function jsonResponse($data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    private function readJsonBody(): ?array
    {
        $raw = file_get_contents("php://input");
        if ($raw === false || trim($raw) === '') {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    private function requireBasicAuth(): void
    {
        $user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($user !== API_BASIC_USER || $pass !== API_BASIC_PASS) {
            header('WWW-Authenticate: Basic realm="mvcapi"');
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }

    // GET /apicar/debug
    public function debug(): void
    {
        $this->jsonResponse([
            'message' => 'Debug de la petición HTTP recibida por la API',
            'request' => $this->debugRequest()
        ], 200);
    }


    private function validateCarPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['brand','model','color','owner'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }

    // GET /apicar/cars  |  POST /apicar/cars
    public function cars(): void
    {
        $this->requireBasicAuth();

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            $cars = $this->modelo->getAll();
            $this->jsonResponse($cars, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $ok = $this->modelo->create($data);
            if ($ok) {
                $this->jsonResponse(["message" => "Car created"], 201);
            }
            $this->jsonResponse(["error" => "Error creating car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /apicar/car/1 | PUT /apicar/car/1 | DELETE /apicar/car/1
    public function car(int $id): void
    {
        $this->requireBasicAuth();

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($id <= 0) {
            $this->jsonResponse(["error" => "Invalid id"], 400);
        }

        if ($method === 'GET') {
            $car = $this->modelo->getById($id);
            if (!$car) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }
            $this->jsonResponse($car, 200);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            // opcional: comprobar existencia antes
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }

            $ok = $this->modelo->update($id, $data);
            if ($ok) {
                $this->jsonResponse(["message" => "Car updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating car"], 500);
        }

        if ($method === 'DELETE') {
            // opcional: comprobar existencia antes
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }

            $ok = $this->modelo->delete($id);
            if ($ok) {
                $this->jsonResponse(["message" => "Car deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }
}