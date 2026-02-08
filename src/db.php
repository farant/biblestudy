<?php
/**
 * Database connection using PDO.
 *
 * Railway provides a DATABASE_URL environment variable like:
 *   postgresql://user:pass@host:port/dbname
 *
 * We parse that and create a PDO connection.
 */

function getDb(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $url = getenv('DATABASE_URL');
    if (!$url) {
        throw new RuntimeException(
            'DATABASE_URL environment variable is not set. '
            . 'Make sure a PostgreSQL database is attached in Railway.'
        );
    }

    $parts = parse_url($url);
    $host = $parts['host'];
    $port = $parts['port'] ?? 5432;
    $dbname = ltrim($parts['path'] ?? '', '/');
    $user = $parts['user'] ?? '';
    $pass = $parts['pass'] ?? '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
