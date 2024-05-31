<?php

namespace App\Application\Actions\RepositoryConnection;

use PDO;

class Connect
{
    private PDO $db;

    public function __construct()
    {

        $dbHost = 'localhost';
        $dbUser = 'postgres';
        $dbPass = 'admin';
        $dbName = 'movie';
        $dbtype = 'pgsql';
        $dbport = '5432';
        $connect = $dbtype . ":host=" . $dbHost . ";dbname=" . $dbName . ";port=" . $dbport;
        $dbConnecion = new PDO($connect, $dbUser, $dbPass);
        $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $dbConnecion;
    }

    function getConnection(): PDO
    {
        return $this->db;
    }
}
