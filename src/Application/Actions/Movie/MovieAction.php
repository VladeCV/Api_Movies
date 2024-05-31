<?php

namespace App\Application\Actions\Movie;

use App\Application\Actions\Action;
use App\Domain\Movie\MovieRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class MovieAction extends Action
{
    protected $repository;

    public function __construct(LoggerInterface $logger, MovieRepository $movieRepository)
    {
        parent::__construct($logger);
        $this->repository = $movieRepository;
    }

    public function action(): Response
    {
        $data = [];
        return $this->respondWithData($data);
    }

    public function getLista(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        $body = ['id_usuario' => '$userUUID', 'body' => 'queryParam'];
        $res = $this->repository->getList($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }

    public function save(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $body = $request->getParsedBody();
        $res = $this->repository->save($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }
}
