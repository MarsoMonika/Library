<?php

class Book
{
    private string $title;
    private string $author;
    private int $publishYear;
    private bool $isAvailable;

    public function __construct(string $title, string $author, int $publishYear, bool $isAvailable)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publishYear = $publishYear;
        $this->isAvailable = $isAvailable;
    }

}