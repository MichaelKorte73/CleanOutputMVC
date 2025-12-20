<?php
namespace CHK\Security;

final class RateLimitPolicy
{
    private int $limit;
    private int $window;

    public function __construct(int $limit = 30, int $window = 60)
    {
        $this->limit  = $limit;
        $this->window = $window;
    }

    public function allow(string $key): bool
    {
        $now = time();

        if (!isset($_SESSION['rpl'][$key])) {
            $_SESSION['rpl'][$key] = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        $entry = &$_SESSION['rpl'][$key];

        // Fenster abgelaufen → reset
        if (($now - $entry['start']) > $this->window) {
            $entry = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        // Limit erreicht
        if ($entry['count'] >= $this->limit) {
            return false;
        }

        $entry['count']++;
        return true;
    }
}