<?php
// Fetch session notes from the database
try {
    $db = getDb();
    $postsStmt = $db->prepare('
        SELECT * FROM posts
        WHERE section = ?
        ORDER BY created_at DESC
    ');
    $postsStmt->execute(['session_notes']);
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

        <h1 class="page-title">Session Notes</h1>
        <p class="page-subtitle">Weekly highlights, quotes, and reflections from our reading group sessions</p>

        <div class="quill-decoration">&#9998;</div>

        <div class="post-form-container" id="posts">
            <details class="post-form-toggle">
                <summary>Add session notes</summary>
                <form action="/post/save" method="POST" class="post-form" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="session_notes">
                    <div class="form-group">
                        <label for="sn-author">Your Name</label>
                        <input type="text" id="sn-author" name="author_name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="sn-title">Session Title</label>
                        <input type="text" id="sn-title" name="title" required placeholder="e.g. Session 1 — February 22, 2025">
                    </div>
                    <div class="form-group">
                        <label for="sn-body">Highlights, Quotes &amp; Notes</label>
                        <textarea id="sn-body" name="body" rows="10" required placeholder="Write up the highlights from this session, favorite quotes, verses, and discussion points..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="sn-image">Image (optional)</label>
                        <input type="file" id="sn-image" name="image" accept="image/*">
                    </div>
                    <div class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button type="submit" class="btn">Post</button>
                </form>
            </details>
        </div>

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No session notes posted yet.
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/_post.php'; ?>
        <?php endforeach; ?>

    </div><!-- end page-content -->
