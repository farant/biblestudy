<?php
/**
 * Shared partial for rendering a single post with its comments.
 * Used by both resources.php and community.php.
 *
 * Expects these variables:
 *   $post        - the post row from the database
 *   $commentStmt - prepared statement for fetching comments
 */
?>
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

    <?php if (isAdmin()): ?>
        <form action="/post/delete" method="POST" class="delete-form"
              onsubmit="return confirm('Delete this entire post and all its comments?')">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <button type="submit" class="btn-delete">&#10005; Delete Post</button>
        </form>
    <?php endif; ?>

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
                <div class="comment-header">
                    <span>
                        <span class="comment-author"><?= e($comment['author_name']) ?></span>
                        <span class="comment-date"><?= formatDateTime($comment['created_at']) ?></span>
                    </span>
                    <?php if (isAdmin()): ?>
                        <form action="/comment/delete" method="POST" class="delete-form-inline"
                              onsubmit="return confirm('Delete this comment?')">
                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                            <button type="submit" class="btn-delete-small">&#10005;</button>
                        </form>
                    <?php endif; ?>
                </div>
                <p><?= nl2br(e($comment['body'])) ?></p>
            </div>
        <?php endforeach; ?>

        <form action="/comment/save" method="POST" class="comment-form">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <div class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
            <div class="form-row">
                <input type="text" name="author_name" placeholder="Your name (optional)" class="comment-name-input">
                <input type="text" name="body" placeholder="Write a comment..." required class="comment-body-input">
                <button type="submit" class="btn btn-small">Reply</button>
            </div>
        </form>
    </div>
</article>
