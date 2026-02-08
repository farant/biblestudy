<?php
/**
 * Simple database migration runner.
 *
 * Tracks which migrations have been applied in a `migrations` table.
 * Run this script to apply any pending migrations:
 *
 *   php migrations/migrate.php
 *
 * Railway can run this automatically on deploy via the Dockerfile.
 */

require_once __DIR__ . '/../src/db.php';

function runMigrations(): void {
    $db = getDb();

    // Create migrations tracking table if it doesn't exist
    $db->exec('
        CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            filename VARCHAR(255) NOT NULL UNIQUE,
            applied_at TIMESTAMP DEFAULT NOW()
        )
    ');

    // Find all .sql migration files, sorted by name
    $files = glob(__DIR__ . '/*.sql');
    sort($files);

    if (empty($files)) {
        echo "No migration files found.\n";
        return;
    }

    // Get list of already-applied migrations
    $stmt = $db->query('SELECT filename FROM migrations');
    $applied = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $count = 0;
    foreach ($files as $file) {
        $filename = basename($file);

        if (in_array($filename, $applied)) {
            echo "  [skip] {$filename} (already applied)\n";
            continue;
        }

        echo "  [apply] {$filename} ... ";

        $sql = file_get_contents($file);
        try {
            $db->exec($sql);
            $db->prepare('INSERT INTO migrations (filename) VALUES (?)')
               ->execute([$filename]);
            echo "OK\n";
            $count++;
        } catch (PDOException $e) {
            echo "FAILED: " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    echo "\nDone. Applied {$count} new migration(s).\n";
}

// Run if called directly
if (php_sapi_name() === 'cli' || realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    echo "Running migrations...\n";
    runMigrations();
}
