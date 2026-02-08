<?php
/**
 * Front controller — all requests come through here.
 *
 * Nginx routes everything to this file. We figure out which page
 * to show based on the URL, then render the right template.
 */

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

// ---- ROUTING ----
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

// Static files are served directly by nginx, so if we get here
// it's a page request.
switch ($path) {
    case '':
    case 'home':
        $page = 'home';
        $pageTitle = "St. Joseph's Catena Aurea Reading Group";
        break;

    case 'resources':
        $page = 'resources';
        $pageTitle = "Resources — St. Joseph's Catena Aurea Reading Group";
        break;

    case 'community':
        $page = 'community';
        $pageTitle = "Community — St. Joseph's Catena Aurea Reading Group";
        break;

    case 'post/create':
        $page = 'post_create';
        $pageTitle = "New Post — St. Joseph's Catena Aurea Reading Group";
        break;

    case 'post/save':
        // Handle form submission
        handlePostSave();
        exit;

    case 'comment/save':
        handleCommentSave();
        exit;

    default:
        http_response_code(404);
        $page = '404';
        $pageTitle = 'Page Not Found';
        break;
}

// ---- RENDER ----
require __DIR__ . '/../templates/layout.php';


// ---- FORM HANDLERS ----

function handlePostSave(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        return;
    }

    $db = getDb();

    $section = $_POST['section'] ?? 'community';
    $authorName = trim($_POST['author_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');

    // Basic validation
    if ($authorName === '' || $body === '') {
        // Redirect back with a simple error
        $redirect = $section === 'study_aids' ? '/resources' : '/community';
        header("Location: {$redirect}?error=missing_fields");
        return;
    }

    // Handle image upload
    $imageUrl = null;
    if (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageUrl = handleImageUpload($_FILES['image']);
    }

    $stmt = $db->prepare('
        INSERT INTO posts (section, author_name, title, body, image_url)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $section,
        $authorName,
        $title ?: null,
        $body,
        $imageUrl,
    ]);

    $redirect = $section === 'study_aids' ? '/resources' : '/community';
    header("Location: {$redirect}#posts");
}

function handleCommentSave(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        return;
    }

    $db = getDb();

    $postId = (int)($_POST['post_id'] ?? 0);
    $authorName = trim($_POST['author_name'] ?? 'Guest');
    $body = trim($_POST['body'] ?? '');

    if ($postId < 1 || $body === '') {
        header('Location: /community');
        return;
    }

    if ($authorName === '') {
        $authorName = 'Guest';
    }

    $stmt = $db->prepare('
        INSERT INTO comments (post_id, author_name, body)
        VALUES (?, ?, ?)
    ');
    $stmt->execute([$postId, $authorName, $body]);

    // Figure out which page to redirect to
    $post = $db->prepare('SELECT section FROM posts WHERE id = ?');
    $post->execute([$postId]);
    $section = $post->fetchColumn();

    $redirect = $section === 'study_aids' ? '/resources' : '/community';
    header("Location: {$redirect}#post-{$postId}");
}

function handleImageUpload(array $file): ?string {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        return null;
    }

    // Max 5MB
    if ($file['size'] > 5 * 1024 * 1024) {
        return null;
    }

    $ext = match($file['type']) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg',
    };

    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = $uploadDir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destPath)) {
        return '/uploads/' . $filename;
    }

    return null;
}
