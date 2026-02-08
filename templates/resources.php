<?php
// Fetch Study Aids posts from database
try {
    $db = getDb();
    $postsStmt = $db->prepare('
        SELECT * FROM posts
        WHERE section = ?
        ORDER BY created_at DESC
    ');
    $postsStmt->execute(['study_aids']);
    $posts = $postsStmt->fetchAll();

    // Fetch comments for each post
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

        <h1 class="page-title">Resources</h1>

        <div class="quill-decoration">&#9998;</div>

        <p class="text-center">
            <strong>We meet at:</strong> 4284 U.S. Route 5 S, McIndoe Falls, VT 05050<br>
            Amanda &mdash; <a href="mailto:amandaalice.arant@gmail.com">amandaalice.arant@gmail.com</a>
            &middot; Fran &mdash; <a href="mailto:francis.arant@gmail.com">francis.arant@gmail.com</a>
        </p>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <!-- ============================================
             STUDY AIDS — blog-style posts with images/text
             ============================================ -->
        <h2 id="posts">Study Aids</h2>

        <!-- New Post Form -->
        <div class="post-form-container">
            <details class="post-form-toggle">
                <summary>Write a new study aid post</summary>
                <form action="/post/save" method="POST" enctype="multipart/form-data" class="post-form">
                    <input type="hidden" name="section" value="study_aids">
                    <div class="form-group">
                        <label for="sa-author">Your Name</label>
                        <input type="text" id="sa-author" name="author_name" required placeholder="Amanda, Fran, etc.">
                    </div>
                    <div class="form-group">
                        <label for="sa-title">Title (optional)</label>
                        <input type="text" id="sa-title" name="title" placeholder="Post title">
                    </div>
                    <div class="form-group">
                        <label for="sa-body">Your Post</label>
                        <textarea id="sa-body" name="body" rows="6" required placeholder="Write your study aid here..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="sa-image">Attach an Image (optional)</label>
                        <input type="file" id="sa-image" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn">Post Study Aid</button>
                </form>
            </details>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">Please fill in your name and post content.</p>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No study aids posted yet. Be the first to share one!
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <article class="blog-post" id="post-<?= $post['id'] ?>">
                <div class="blog-post-header">
                    <span class="blog-post-author"><?= e($post['author_name']) ?></span>
                    <span class="blog-post-date"><?= formatDate($post['created_at']) ?></span>
                </div>
                <?php if ($post['title']): ?>
                    <h3 class="blog-post-title"><?= e($post['title']) ?></h3>
                <?php endif; ?>
                <div class="blog-post-body">
                    <?= nl2br(e($post['body'])) ?>
                    <?php if ($post['image_url']): ?>
                        <img src="<?= e($post['image_url']) ?>" alt="Post image">
                    <?php endif; ?>
                </div>

                <!-- Comments -->
                <div class="blog-post-comments">
                    <h4>Comments</h4>
                    <?php
                    $commentStmt->execute([$post['id']]);
                    $comments = $commentStmt->fetchAll();
                    ?>
                    <?php if (empty($comments)): ?>
                        <p class="no-comments">No comments yet.</p>
                    <?php endif; ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="blog-comment">
                            <span class="comment-author"><?= e($comment['author_name']) ?></span>
                            <span class="comment-date"><?= formatDateTime($comment['created_at']) ?></span>
                            <p><?= nl2br(e($comment['body'])) ?></p>
                        </div>
                    <?php endforeach; ?>

                    <form action="/comment/save" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <div class="form-row">
                            <input type="text" name="author_name" placeholder="Your name (optional)" class="comment-name-input">
                            <input type="text" name="body" placeholder="Write a comment..." required class="comment-body-input">
                            <button type="submit" class="btn btn-small">Reply</button>
                        </div>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <h2>Commentaries Online</h2>
        <div class="card-grid">
            <div class="card">
                <h3>Catena Aurea</h3>
                <p><a href="https://www.ecatholic2000.com/catena/untitled-111.shtml">Read Online &rarr;</a></p>
            </div>
            <div class="card">
                <h3>Haydock Catholic Bible Commentary</h3>
                <p><a href="https://haydockcommentary.com/">Read Online &rarr;</a></p>
            </div>
            <div class="card">
                <h3>Cornelius a Lapide Commentary</h3>
                <p><a href="https://www.ecatholic2000.com/lapide/untitled-170.shtml">Read Online &rarr;</a></p>
            </div>
        </div>

    </div><!-- end page-content -->
