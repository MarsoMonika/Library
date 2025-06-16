<?php

class Database
{
    private string $host;
    private string $user;
    private string $pass;
    private string $db;
    private ?PDO $conn = null;

    public function __construct(
        string $host = 'localhost',
        string $db = 'Library',
        string $user = '',
        string $pass = ''
    )
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect(): PDO
    {
        if ($this->conn == null) {
            $dsn = "sqlsrv:Server=$this->host;Database=$this->db";
            try {
                $this->conn = new PDO($dsn, $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Connection failed" . $e->getMessage());
            }
        }
        return $this->conn;
    }
}