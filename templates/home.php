<?php
// Fetch "Currently Reading" from the database
try {
    $db = getDb();
    $bookStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_book'");
    $bookStmt->execute();
    $currentBook = $bookStmt->fetchColumn() ?: 'The Gospel According to St. Matthew';

    $chapterStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_chapter'");
    $chapterStmt->execute();
    $currentChapter = $chapterStmt->fetchColumn() ?: 'Chapter 1, Verses 1–17';

    // Fetch Prayer Intentions posts
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
    $currentBook = 'The Gospel According to St. Matthew';
    $currentChapter = 'Chapter 1, Verses 1–17';
    $posts = [];
}
?>

    <!-- ======== CURRENTLY READING ======== -->
    <section class="currently-reading">
        <p class="currently-reading-label">Currently Reading</p>
        <p class="currently-reading-title"><?= e($currentBook) ?></p>
        <p class="currently-reading-chapter"><?= e($currentChapter) ?></p>
        <p class="currently-reading-pace">Please read at your own pace!</p>
    </section>

    <!-- ======== ANNOUNCEMENTS ======== -->
    <section class="announcements">
        <h2>Announcements</h2>
        <p>
            Please bring any prayer intentions for our group that you would like
            to share. And please bring any favorite or interesting quotes you'd
            like to discuss together! Have a blessed week and please stay warm
            and healthy.
        </p>
        <p>
            We are hopeful some snow might melt and help the parking situation!
        </p>
    </section>

    <!-- ======== PRAYER INTENTIONS ======== -->
    <div class="page-content">
        <h2 id="prayers">Prayer Intentions</h2>
        <p class="text-center" style="margin-bottom: 1.5rem;">
            A place for anyone to share needed prayers.
        </p>

        <div class="post-form-container">
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

        <?php if (empty($posts)): ?>
            <p class="text-center" style="color: #c4a95a; margin: 1.5rem 0;">
                No prayer intentions posted yet.
            </p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/_post.php'; ?>
        <?php endforeach; ?>
    </div>

    <section class="home-blessing">
        <div class="ornament">&#10022; &#9670; &#10022;</div>
        <p class="blessing-text">
            Welcome to our reading group site!<br>
            Feel free to post questions and comments.<br>
            God bless you!
        </p>
        <div class="quill-inkwell-decoration">
            <img src="/images/quill-inkwell.png" alt="Quill and Inkwell">
        </div>
        <div class="ornament">&#10022; &#9670; &#10022;</div>
    </section>
