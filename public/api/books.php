<?php

require_once __DIR__ . '/../../classes/BookRepository.php';
require_once __DIR__ . '/../../classes/Database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];
try {
    $db = new Database();
    $bookRepository = new BookRepository($db);

    switch ($method) {
        case "GET":
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $book = $bookRepository->getById((int)$_GET["id"]);
                if ($book) {
                    echo json_encode($book, JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Book not found.']);
                }
            } else {
                $books = $bookRepository->getAll();
                echo json_encode($books, JSON_UNESCAPED_UNICODE);
            }
            break;
        case "POST":
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON.']);
                break;
            }
            $book = new Book(
                $data["title"] ?? 'null',
                $data["author"] ?? '',
                (int)$data["publishYear"] ?? 0,
                (bool)$data["isAvailable"] ?? false,
                0
            );
            if ($bookRepository->add($book)) {
                http_response_code(201);
                echo json_encode(['success' => 'Book added.']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not added.']);
            }
            break;

        case
        "PUT":
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON.']);
                break;
            }
            $book = new Book(
                $data["title"] ?? '',
                $data["author"] ?? '',
                (int)$data["publishYear"] ?? 0,
                (bool)$data["isAvailable"] ?? false,
                (int)$data["id"] ?? 0
            );
            if ($bookRepository->update($book)) {
                http_response_code(200);
                echo json_encode(['success' => 'Book updated.']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not updated.']);
            }
            break;
        case "DELETE":

            $id = null;
            if (isset($_GET["id"])) {
                $id = (int)$_GET["id"];
            } else {
                $data = json_decode(file_get_contents("php://input"), true);
                $id = $data["id"] ?? 0;
            }
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing "id".']);
                break;
            }
            if ($bookRepository->delete($id)) {
                http_response_code(204);
                echo json_encode(['success' => 'Book deleted.']);
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
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

