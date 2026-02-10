<?php
// Fetch Prayer Intentions posts from database
try {
    $db = getDb();
    $postsStmt = $db->prepare('
        SELECT * FROM posts
        WHERE section = ?
        ORDER BY created_at DESC
    ');
    $postsStmt->execute(['prayer_intentions']);
    $posts = $postsStmt->fetchAll();

    $commentStmt = $db->prepare('
        SELECT * FROM comments
        WHERE post_id = ?
        ORDER BY created_at ASC
    ');
} catch (Exception $e) {
    $posts = [];
}
?>

    <div class="page-content">

        <h1 class="page-title">Prayer Intentions</h1>
        <p class="page-subtitle">A place for anyone to share needed prayers</p>

        <div class="quill-decoration">&#9998;</div>

        <div class="post-form-container" id="posts">
            <details class="post-form-toggle">
                <summary>Share a prayer intention</summary>
                <form action="/post/save" method="POST" class="post-form">
                    <input type="hidden" name="section" value="prayer_intentions">
                    <div class="form-group">
                        <label for="pi-author">Your Name</label>
                        <input type="text" id="pi-author" name="author_name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="pi-body">Prayer Intention</label>
                        <textarea id="pi-body" name="body" rows="4" required placeholder="Share your prayer intention..."></textarea>
                    </div>
                    <div class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button type="submit" class="btn">Post</button>
                </form>
            </details>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">Please fill in your name and prayer intention.</p>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No prayer intentions posted yet. Be the first to share one.
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/_post.php'; ?>
        <?php endforeach; ?>

    </div><!-- end page-content -->
