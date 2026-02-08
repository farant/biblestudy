<?php
/**
 * Helper functions used across the site.
 */

/**
 * Safely escape output for HTML.
 */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Format a timestamp for display.
 */
function formatDate(string $timestamp): string {
    $dt = new DateTime($timestamp);
    return $dt->format('F j, Y');
}

/**
 * Format a timestamp with time for display.
 */
function formatDateTime(string $timestamp): string {
    $dt = new DateTime($timestamp);
    return $dt->format('F j, Y \a\t g:i A');
}

/**
 * Get the current page name from the URL for nav highlighting.
 */
function currentPage(): string {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($path, '/');
    if ($path === '' || $path === 'index.php') {
        return 'home';
    }
    return $path;
}

/**
 * Check if a nav link is the active page.
 */
function isActive(string $page): string {
    return currentPage() === $page ? ' class="active"' : '';
}
