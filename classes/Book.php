<?php

class Book
{
    private string $title;
    private string $author;
    private int $publishYear;
    private bool $isAvailable;
    private ?int $id;

    public function __construct(string $title, string $author, int $publishYear, bool $isAvailable, ?int $id = null)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publishYear = $publishYear;
        $this->isAvailable = $isAvailable;
        $this->id = $id;
    }
    public function toArray() : array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'author' => $this->getAuthor(),
            'publishYear' => $this->getPublishYear(),
            'isAvailable' => $this->isAvailable()
        ];

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPublishYear(): int
    {
        return $this->publishYear;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

}