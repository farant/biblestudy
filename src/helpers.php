<?php
/**
 * Helper functions used across the site.
 */

// Start session for admin login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

/**
 * Check if the current user is logged in as admin.
 */
function isAdmin(): bool {
    return !empty($_SESSION['admin_id']);
}

/**
 * Get the logged-in admin's display name.
 */
function adminName(): string {
    return $_SESSION['admin_name'] ?? '';
}

/**
 * Generate a CSRF token for forms.
 */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify a submitted CSRF token.
 */
function verifyCsrf(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
