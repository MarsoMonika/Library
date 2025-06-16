<?php

require_once 'Book.php';
require_once 'Database.php';

class BookRepository
{
    private PDO $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }

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

    public function getAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM books");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $books = [];

        foreach ($rows as $row) {
            $books[] = new Book(
                $row['Title'],
                $row['Author'],
                (int)$row['PublishYear'],
                (bool)$row['IsAvailable'],
                (int)$row['Id']
            );
        }
        return $books;
    }

    public function getById(int $id): ?Book
    {
        $stmt = $this->conn->prepare("SELECT * FROM books WHERE Id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return new Book(
            $row['Title'],
            $row['Author'],
            (int)$row['PublishYear'],
            (int)$row['IsAvailable'],
            (int)$row['Id']
        );
    }

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

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM books WHERE Id = ?");
        return $stmt->execute([$id]);
    }
}