<?php
// Fetch "Currently Reading" from the database
try {
    $db = getDb();
    $bookStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_book'");
    $bookStmt->execute();
    $currentBook = $bookStmt->fetchColumn() ?: 'The Gospel According to St. Matthew';

    $chapterStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_chapter'");
    $chapterStmt->execute();
    $currentChapter = $chapterStmt->fetchColumn() ?: 'Chapter 2, Pages 62–86';

    $topicStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_topic'");
    $topicStmt->execute();
    $currentTopic = $topicStmt->fetchColumn() ?: '';

    // Fetch Quote of the Week
    $quoteStmt = $db->prepare("SELECT value FROM settings WHERE key = 'quote_of_the_week_text'");
    $quoteStmt->execute();
    $quoteText = $quoteStmt->fetchColumn() ?: '';

    $quoteSourceStmt = $db->prepare("SELECT value FROM settings WHERE key = 'quote_of_the_week_source'");
    $quoteSourceStmt->execute();
    $quoteSource = $quoteSourceStmt->fetchColumn() ?: '';
} catch (Exception $e) {
    $currentBook = 'The Gospel According to St. Matthew';
    $currentChapter = 'Chapter 2, Pages 62–86';
    $currentTopic = '';
    $quoteText = '';
    $quoteSource = '';
}
?>

    <!-- ======== CURRENTLY READING ======== -->
    <section class="currently-reading">
        <p class="currently-reading-label">Currently Reading</p>
        <p class="currently-reading-title"><?= e($currentBook) ?></p>
        <p class="currently-reading-chapter"><?= e($currentChapter) ?></p>
        <?php if ($currentTopic): ?>
            <p class="currently-reading-topic"><?= e($currentTopic) ?></p>
        <?php endif; ?>
        <p class="currently-reading-pace">Please read at your own pace!</p>
        <a href="https://a.co/d/07pyCbSM" class="book-cover-link" target="_blank">
            <img src="/images/catena-aurea-cover.jpg" alt="Catena Aurea, Volume 1 — available on Amazon" class="book-cover" style="width: 100px; height: auto;">
        </a>
    </section>

    <!-- ======== ANNOUNCEMENTS ======== -->
    <section class="announcements">
        <h2>Announcements</h2>
        <p>
            Thank you for coming to the first session! We hope everyone stays warm
            and healthy. For the next session please bring your favorite quotes!
        </p>
        <p>
            And all who could not come but wanted to &mdash; you were missed!
        </p>
    </section>

    <!-- ======== QUOTE OF THE WEEK ======== -->
    <?php if ($quoteText): ?>
    <section class="quote-of-the-week">
        <h2>Quote of the Week</h2>
        <div class="quote-card">
            <blockquote><?= nl2br(e($quoteText)) ?></blockquote>
            <?php if ($quoteSource): ?>
                <p class="attribution">&mdash; <?= e($quoteSource) ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

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
