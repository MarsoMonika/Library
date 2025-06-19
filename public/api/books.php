<?php
require_once __DIR__ . '/../../controllers/BookController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$controller = new BookController();
$method = $_SERVER["REQUEST_METHOD"];

try {
    switch ($method) {
        case "GET":

            if (isset($_GET["id"])) {
                $result = $controller->get((int)$_GET["id"]);

                if ($result) {
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Book not found.']);
                }

            } else if (isset($_GET["search"])) {
                echo json_encode($controller->search($_GET["search"]), JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($controller->list(), JSON_UNESCAPED_UNICODE);
            }
            break;

        case "POST":
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON.']);
                break;
            }
            $ok = $controller->create($data);

            if ($ok) {
                http_response_code(201);
                echo json_encode(['success' => 'Book added.']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not added.']);
            }
            break;

        case "PUT":
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || !isset($data["id"])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing or invalid "id".']);
                break;
            }
            $ok = $controller->update($data);

            if ($ok) {
                echo json_encode(['success' => 'Book updated.']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not updated.']);
            }
            break;

        case "DELETE":
            $id = $_GET["id"] ?? null;

            if (!$id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $id = $data["id"] ?? null;
            }

            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing "id".']);
                break;
            }
            $ok = $controller->delete((int)$id);

            if ($ok) {
                http_response_code(204);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not deleted.']);
            }
            break;

        case "OPTIONS":
            http_response_code(204);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}