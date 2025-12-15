<?php
/**
 * Response
 *
 * Author: Michael Korte
 * Mail: mkorte@korte-software.de
 * Company: Michael Korte Software
 * Version: 0.1
 * Date: 13.12.2025
 *
 * Static response helper.
 *
 * Responsibilities:
 * - Send HTTP headers
 * - Output response body
 * - Define response type (HTML / JSON / Redirect)
 *
 * Notes:
 * - No buffering
 * - No templating
 * - No side effects beyond headers & output
 */
namespace CHK\Core;

final class Response
{
    /**
     * Send an HTML response.
     *
     * @param string $content HTML output
     * @param int    $status  HTTP status code
     */
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');

        echo $content;
    }

    /**
     * Send a JSON response.
     *
     * @param array $data   Data to encode as JSON
     * @param int   $status HTTP status code
     */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Send a redirect response.
     *
     * @param string $url    Target URL
     * @param int    $status HTTP status code (301 / 302)
     */
    public static function redirect(string $url, int $status = 301): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }
}