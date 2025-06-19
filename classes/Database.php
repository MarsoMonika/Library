<?php

//handles database connection with use of PDO to be more secure
class Database
{
    private string $host;
    private string $user;
    private string $pass;
    private string $db;
    private ?PDO $conn = null;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db   = getenv('DB_NAME') ?: 'Library';
        $this->user = getenv('DB_USER') ?: '';
        $this->pass = getenv('DB_PASS') ?: '';
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