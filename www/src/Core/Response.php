<?php
namespace CHK\Core;

class Response
{
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');
        echo $content;
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    public static function redirect(string $url, int $status = 301): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }
}