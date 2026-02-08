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

    case 'admin':
        $page = 'admin_login';
        $pageTitle = "Admin Login — St. Joseph's Catena Aurea Reading Group";
        break;

    case 'admin/login':
        handleAdminLogin();
        exit;

    case 'admin/logout':
        handleAdminLogout();
        exit;

    case 'admin/password':
        $page = 'admin_password';
        $pageTitle = "Change Password — St. Joseph's Catena Aurea Reading Group";
        break;

    case 'admin/password/save':
        handlePasswordChange();
        exit;

    case 'post/save':
        handlePostSave();
        exit;

    case 'post/delete':
        handlePostDelete();
        exit;

    case 'comment/save':
        handleCommentSave();
        exit;

    case 'comment/delete':
        handleCommentDelete();
        exit;

    default:
        http_response_code(404);
        $page = '404';
        $pageTitle = 'Page Not Found';
        break;
}

// ---- RENDER ----
require __DIR__ . '/../templates/layout.php';


// ---- ADMIN HANDLERS ----

function handleAdminLogin(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /admin');
        return;
    }

    $db = getDb();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare('SELECT id, display_name, password_hash FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['display_name'];
        $_SESSION['admin_username'] = $username;
        header('Location: /?welcome=1');
    } else {
        header('Location: /admin?error=invalid');
    }
}

function handleAdminLogout(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: /');
}

function handlePasswordChange(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
        header('Location: /admin');
        return;
    }

    if (!verifyCsrf()) {
        header('Location: /admin/password?error=csrf');
        return;
    }

    $db = getDb();
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword === '' || strlen($newPassword) < 6) {
        header('Location: /admin/password?error=short');
        return;
    }

    if ($newPassword !== $confirmPassword) {
        header('Location: /admin/password?error=mismatch');
        return;
    }

    // Verify current password
    $stmt = $db->prepare('SELECT password_hash FROM admins WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($currentPassword, $hash)) {
        header('Location: /admin/password?error=wrong');
        return;
    }

    // Update password
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $db->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
    $stmt->execute([$newHash, $_SESSION['admin_id']]);

    header('Location: /admin/password?success=1');
}


// ---- POST/COMMENT HANDLERS ----

function handlePostSave(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        return;
    }

    if (isSpam()) {
        header('Location: /community');
        return;
    }

    $db = getDb();

    $section = $_POST['section'] ?? 'community';
    $authorName = trim($_POST['author_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');

    // Basic validation
    if ($authorName === '' || $body === '') {
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

function handlePostDelete(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
        header('Location: /');
        return;
    }

    if (!verifyCsrf()) {
        header('Location: /');
        return;
    }

    $db = getDb();
    $postId = (int)($_POST['post_id'] ?? 0);

    if ($postId < 1) {
        header('Location: /');
        return;
    }

    // Get post info before deleting
    $stmt = $db->prepare('SELECT section, image_url FROM posts WHERE id = ?');
    $stmt->execute([$postId]);
    $post = $stmt->fetch();
    $section = $post['section'] ?? 'community';

    // Delete the uploaded image from disk
    if (!empty($post['image_url'])) {
        $filePath = '/railway-volume' . $post['image_url'];
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    // Delete post (comments cascade automatically)
    $stmt = $db->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$postId]);

    $redirect = $section === 'study_aids' ? '/resources' : '/community';
    header("Location: {$redirect}#posts");
}

function handleCommentSave(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        return;
    }

    if (isSpam()) {
        header('Location: /community');
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

function handleCommentDelete(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin()) {
        header('Location: /');
        return;
    }

    if (!verifyCsrf()) {
        header('Location: /');
        return;
    }

    $db = getDb();
    $commentId = (int)($_POST['comment_id'] ?? 0);

    if ($commentId < 1) {
        header('Location: /');
        return;
    }

    // Get post info for redirect
    $stmt = $db->prepare('
        SELECT p.id, p.section FROM comments c
        JOIN posts p ON p.id = c.post_id
        WHERE c.id = ?
    ');
    $stmt->execute([$commentId]);
    $post = $stmt->fetch();

    $stmt = $db->prepare('DELETE FROM comments WHERE id = ?');
    $stmt->execute([$commentId]);

    if ($post) {
        $redirect = $post['section'] === 'study_aids' ? '/resources' : '/community';
        header("Location: {$redirect}#post-{$post['id']}");
    } else {
        header('Location: /community');
    }
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

    $uploadDir = '/railway-volume/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.jpg';
    $destPath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return null;
    }

    // Resize and convert to JPEG
    $resized = resizeImage($destPath, 1200, 80);
    if ($resized) {
        return '/uploads/' . $filename;
    }

    // If resize fails, keep the original
    return '/uploads/' . $filename;
}

function resizeImage(string $path, int $maxDim, int $quality): bool {
    $src = @imagecreatefromstring(file_get_contents($path));
    if (!$src) {
        return false;
    }

    $origW = imagesx($src);
    $origH = imagesy($src);

    // Only resize if larger than max dimension
    if ($origW > $maxDim || $origH > $maxDim) {
        if ($origW >= $origH) {
            $newW = $maxDim;
            $newH = (int) round($origH * ($maxDim / $origW));
        } else {
            $newH = $maxDim;
            $newW = (int) round($origW * ($maxDim / $origH));
        }

        $dst = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);
        $src = $dst;
    }

    $result = imagejpeg($src, $path, $quality);
    imagedestroy($src);
    return $result;
}
