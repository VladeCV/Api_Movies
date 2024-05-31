<?php

namespace App\Infrastructure\Persistence\Movie;

use App\Application\Actions\RepositoryConnection\Connect;
use PDO;
use App\Domain\Movie\MovieRepository;

class DataMovieRepository implements MovieRepository
{
    private PDO $db;
    private array $movies = [
        [
            'id' => 1,
            'title' => 'The Shawshank Redemption',
            'category' => 'Drama',
            'release_year' => 1994
        ],
        [
            'id' => 2,
            'title' => 'The Godfather',
            'category' => 'Crime',
            'release_year' => 1972
        ],
        [
            'id' => 3,
            'title' => 'The Dark Knight',
            'category' => 'Action',
            'release_year' => 2008
        ],
        [
            'id' => 4,
            'title' => 'The Lord of the Rings: The Return of the King',
            'category' => 'Adventure',
            'release_year' => 2003
        ],
        [
            'id' => 5,
            'title' => 'Pulp Fiction',
            'category' => 'Crime',
            'release_year' => 1994
        ],
        [
            'id' => 6,
            'title' => 'Schindler\'s List',
            'category' => 'Biography',
            'release_year' => 1993
        ],
        [
            'id' => 7,
            'title' => 'Inception',
            'category' => 'Action',
            'release_year' => 2010
        ],
        [
            'id' => 8,
            'title' => 'Fight Club',
            'category' => 'Drama',
            'release_year' => 1999
        ],
        [
            'id' => 9,
            'title' => 'Forrest Gump',
            'category' => 'Drama',
            'release_year' => 1994
        ],
        [
            'id' => 10,
            'title' => 'The Matrix',
            'category' => 'Action',
            'release_year' => 1999
        ]
    ];


    public function __construct()
    {
        /*$con = new Connect();
        $this->db = $con->getConnection();*/
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
            return ['data' => [], "message" => 'Title not in body', 'statusCode' => 200, 'success' => false];
        }
        if (!isset($body['category'])) {
            return ['data' => [], "message" => 'Category not in body', 'statusCode' => 200, 'success' => false];
        }
        if (!isset($body['releaseYear'])) {
            return ['data' => [], "message" => 'Release year not in body', 'statusCode' => 200, 'success' => false];
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

    public function getListInMemory($body): array
    {
        return ['data' => $this->movies, 'message' => 'Data list', 'statusCode' => 200, 'success' => true];
    }

    public function saveInMemory($body): array
    {
        $movieCategory = ['Action', 'Science', 'Fiction', 'Drama', 'Thriller', 'Horror', 'Comedy'];

        if (!isset($body['title']) || empty($body['title'])) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Title not in body'

            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }
        if (!isset($body['category']) || empty($body['category'])) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Category not in body'

            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }
        if (!isset($body['releaseYear'])) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Release Year field must have values between 1888 and the current year (2024)'

            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }

        if (($body['releaseYear'] >= 2025) || ($body['releaseYear'] <= 1888)) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Release Year field must have values between 1888 and the current year (2024)'

            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }

        if (strlen($body['title']) > 51) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Title must be 50 characters or fewer'
            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }

        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $body['title'])) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Title field should only allow letters, numbers, and spaces.'
            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }

        if (!in_array($body['category'], $movieCategory)) {
            $data = [
                'detail' => 'Unable to create the movie',
                'message' => 'Category field must be: Action, Science Fiction, Drama, Thriller, Horror, Comedy'
            ];
            return ['data' => $data, "message" => 'Bad Request', 'statusCode' => 202, 'success' => false];
        }

        $title = $body['title'];
        $category = $body['category'];
        $releaseYear = $body['releaseYear'];

        try {
            $movie = [
                'id' => count($this->movies) + 1,
                'title' => $title,
                'category' => $category,
                'release_year' => $releaseYear
            ];

            $this->movies[] = $movie;

            return ['data' => $movie, 'message' => 'Movie created', 'statusCode' => 200, 'success' => true];
        } catch (\Exception $ex) {
            return ['data' => $ex, 'message' => 'Unable to create movie', 'statusCode' => 200, 'success' => true];
        }
    }
}
