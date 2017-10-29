<?php

namespace LauLamanApps\IzettleApi;

use Psr\Http\Message\ResponseInterface;

interface IzettleClientInterface
{
    public function get(string $url, ?array $queryParameters = null): ResponseInterface;

    public function post(string $url, string $jsonData): void;

    public function put(string $url, string $jsonData): void;

    public function delete(string $url): void;

    public function getJson(ResponseInterface $response): string;
}
