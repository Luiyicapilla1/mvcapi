<?php

namespace lgc\mvc2app;
use lgc\mvc2app\Controlador;

class ApiCar extends Controlador
{
    public function __construct(){
        $this->modelo = $this->modelo('articulo');
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

    private function validateCarPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['titulo'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }


    public function articulos(){
        //$this->requireBasicAuth();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method == "GET") {
            $data = $this->modelo->obtenerArticulos();
            $this->jsonResponse($data, 200);
        }

        if ($method == "POST") {
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
    

    public function articulo($id): void{
        //$this->requireBasicAuth();

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

        if ($method == "PUT") {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            // opcional: comprobar existencia antes
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Articulo not found"], 404);
            }

            $ok = $this->modelo->update($id, $data);
            if ($ok) {
                $this->jsonResponse(["message" => "Articulo updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating Articulo"], 500);
        }

        if ($method == "DELETE") {
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Articulo not found"], 404);
            }

            $ok = $this->modelo->delete($id);
            if ($ok) {
                $this->jsonResponse(["message" => "Articulo deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting Articulo"], 500);
        }
        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }
}