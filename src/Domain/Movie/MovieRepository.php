<?php

namespace App\Domain\Movie;

interface MovieRepository
{
    public function getList($body): array;

    public function save($body): array;
}