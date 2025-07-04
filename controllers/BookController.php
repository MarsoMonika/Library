<?php
require_once __DIR__ . '/../classes/BookRepository.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Book.php';

class BookController {
    private BookRepository $bookRepository;

    public function __construct() {
        $db = new Database();
        $this->bookRepository = new BookRepository($db);
    }

    //returns the lis of all books
    public function list() {
        return $this->bookRepository->getAll();
    }

    //getting a book by id
    public function get($id) {
        return $this->bookRepository->getById($id);
    }

    //creates a new record by the given data
    public function create($data) {
        $book = new Book(
            $data["title"] ?? '',
            $data["author"] ?? '',
            (int)($data["publishYear"] ?? 0),
            (bool)($data["isAvailable"] ?? false)
        );

        return $this->bookRepository->add($book);
    }

    //updates the record by the provided data
    public function update($data) {
        $book = new Book(
            $data["title"] ?? '',
            $data["author"] ?? '',
            (int)($data["publishYear"] ?? 0),
            (bool)($data["isAvailable"] ?? false),
            (int)($data["id"] ?? 0)
        );

        return $this->bookRepository->update($book);
    }

    //deletes the record by id
    public function delete($id) {
        return $this->bookRepository->delete($id);
    }

    //search for books by query string
    public function search($q) {
        return $this->bookRepository->search($q);
    }
}