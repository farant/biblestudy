<?php
// Fetch commentator biographies from the database
try {
    $db = getDb();
    $postsStmt = $db->prepare('
        SELECT * FROM posts
        WHERE section = ?
        ORDER BY created_at ASC
    ');
    $postsStmt->execute(['commentators']);
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

        <h1 class="page-title">Commentators</h1>
        <p class="page-subtitle">Biographies of the Church Fathers and commentators featured in the Catena Aurea</p>

        <div class="quill-decoration">&#9998;</div>

        <div class="post-form-container" id="posts">
            <details class="post-form-toggle">
                <summary>Add a commentator biography</summary>
                <form action="/post/save" method="POST" class="post-form">
                    <input type="hidden" name="section" value="commentators">
                    <div class="form-group">
                        <label for="cm-author">Your Name</label>
                        <input type="text" id="cm-author" name="author_name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="cm-title">Commentator Name</label>
                        <input type="text" id="cm-title" name="title" required placeholder="e.g. St. John Chrysostom">
                    </div>
                    <div class="form-group">
                        <label for="cm-body">Biography</label>
                        <textarea id="cm-body" name="body" rows="6" required placeholder="Write a biography of this commentator..."></textarea>
                    </div>
                    <div class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button type="submit" class="btn">Post</button>
                </form>
            </details>
        </div>

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No commentator biographies posted yet.
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/_post.php'; ?>
        <?php endforeach; ?>

    </div><!-- end page-content -->
