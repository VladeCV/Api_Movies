<?php

namespace App\Infrastructure\Persistence\Movie;

use App\Application\Actions\RepositoryConnection\Connect;
use PDO;
use App\Domain\Movie\MovieRepository;

class DataMovieRepository implements MovieRepository
{
    private PDO $db;

    public function __construct()
    {
        $con = new Connect();
        $this->db = $con->getConnection();
    }

    public function getList($body): array
    {
        $sql = "
            SELECT *
            FROM movie
        ";
        $res = ($this->db)->prepare($sql);
        $res->execute();

        $movieList = $res->fetchAll(PDO::FETCH_ASSOC);

        return ['data' => $movieList, 'message' => 'Data list', 'statusCode' => 200, 'success' => true];
    }

    public function save($body): array
    {
        if (!isset($body['title'])) {
            return ['data' => array(), "message" => 'Title not in body', 'statusCode' => 200, 'success' => false];
        }
        if (!isset($body['category'])) {
            return ['data' => array(), "message" => 'Category not in body', 'statusCode' => 200, 'success' => false];
        }
        if (!isset($body['releaseYear'])) {
            return ['data' => array(), "message" => 'Release year not in body', 'statusCode' => 200, 'success' => false];
        }

        $title = $body['title'];
        $category = $body['category'];
        $releaseYear = $body['releaseYear'];

        try {
            $sql = "
                INSERT INTO movie(
                    id,
                    title,
                    category,
                    release_year         
                )VALUES (
                    '12',
                    :title,
                    :category,
                    :release_year
                )
            ";
            $res = ($this->db)->prepare($sql);
            $res->bindParam(':title', $title, PDO::PARAM_STR);
            $res->bindParam(':category', $category, PDO::PARAM_STR);
            $res->bindParam(':release_year', $releaseYear, PDO::PARAM_INT);
            $res->execute();

            $data = [
                'title' => $title,
                'category' => $category
            ];

            return ['data' => $data, 'message' => 'Movie created', 'statusCode' => 200, 'success' => true];


        } catch (\Exception $ex) {
            return ['data' => $ex, 'message' => 'Unable to create movie', 'statusCode' => 200, 'success' => true];
        }

    }
}
