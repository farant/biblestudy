<?php
// Fetch Community posts from database
try {
    $db = getDb();
    $postsStmt = $db->prepare('
        SELECT * FROM posts
        WHERE section = ?
        ORDER BY created_at DESC
    ');
    $postsStmt->execute(['community']);
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

        <h1 class="page-title">Community</h1>
        <p class="page-subtitle">A place to share thoughts, resources, and questions</p>

        <div class="quill-decoration">&#9998;</div>

        <p class="text-center">
            Share a reflection, a quote, a question, a picture, or anything
            related to our study. Everyone is welcome to post!
        </p>

        <p class="text-center" style="margin-bottom: 2rem;">
            <strong>Contact us:</strong><br>
            Amanda &mdash; <a href="mailto:amandaalice.arant@gmail.com">amandaalice.arant@gmail.com</a><br>
            Fran &mdash; <a href="mailto:francis.arant@gmail.com">francis.arant@gmail.com</a>
        </p>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <!-- New Post Form -->
        <div class="post-form-container" id="posts">
            <details class="post-form-toggle">
                <summary>Write a new post</summary>
                <form action="/post/save" method="POST" enctype="multipart/form-data" class="post-form">
                    <input type="hidden" name="section" value="community">
                    <div class="form-group">
                        <label for="cm-author">Your Name</label>
                        <input type="text" id="cm-author" name="author_name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="cm-title">Title (optional)</label>
                        <input type="text" id="cm-title" name="title" placeholder="Post title">
                    </div>
                    <div class="form-group">
                        <label for="cm-body">Your Post</label>
                        <textarea id="cm-body" name="body" rows="6" required placeholder="Share a thought, question, quote, or anything..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cm-image">Attach an Image (optional)</label>
                        <input type="file" id="cm-image" name="image" accept="image/*">
                    </div>
                    <div class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <button type="submit" class="btn">Post</button>
                </form>
            </details>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">Please fill in your name and post content.</p>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No posts yet. Be the first to share something!
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/_post.php'; ?>
        <?php endforeach; ?>

    </div><!-- end page-content -->
