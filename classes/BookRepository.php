<?php

require_once 'Book.php';
require_once 'Database.php';

//connecting the database to the controller level, crud methods are defined here

class BookRepository
{
    private PDO $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

    // adding a book to the records
    public function add(Book $book): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO books (Title, Author, PublishYear, IsAvailable) VALUES (?,?,?,?)"
        );
        return $stmt->execute([
            $book->getTitle(),
            $book->getAuthor(),
            $book->getPublishYear(),
            (int)$book->isAvailable()
        ]);
    }

    //fetching all the books from database
    public function getAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM books");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        foreach ($rows as $row) {
            $books[] = (new Book(
                $row['Title'],
                $row['Author'],
                (int)$row['PublishYear'],
                (bool)$row['IsAvailable'],
                (int)$row['Id']
            ))->toArray();
        }
        return $books;
    }

    //fetching one book
    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM books WHERE Id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        $book = new Book(
            $row['Title'],
            $row['Author'],
            (int)$row['PublishYear'],
            (bool)$row['IsAvailable'],
            (int)$row['Id']
        );
        return $book->toArray();
    }

    //updating a book by id
    public function update(Book $book): bool
    {
        $stmt = $this->conn->prepare("UPDATE books SET Title = ?, Author = ?, PublishYear = ?, IsAvailable = ? WHERE Id = ?"
        );
        return $stmt->execute([
            $book->getTitle(),
            $book->getAuthor(),
            $book->getPublishYear(),
            (int)$book->isAvailable(),
            $book->getId()
        ]);
    }

    // deleting a book by id
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM books WHERE Id = ?");
        return $stmt->execute([$id]);
    }

    //searching books by id or author
    public function search($query): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM books WHERE Title LIKE :q1 OR Author LIKE :q2"
        );
        $like = "%$query%";
        $stmt->bindParam(':q1', $like, PDO::PARAM_STR);
        $stmt->bindParam(':q2', $like, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $books = [];
        foreach ($rows as $row) {
            $books[] = (new Book(
                $row['Title'],
                $row['Author'],
                (int)$row['PublishYear'],
                (bool)$row['IsAvailable'],
                (int)$row['Id']
            ))->toArray();
        }
        return $books;
    }
}