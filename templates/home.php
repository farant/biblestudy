<?php
// Fetch "Currently Reading" from the database
try {
    $db = getDb();
    $bookStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_book'");
    $bookStmt->execute();
    $currentBook = $bookStmt->fetchColumn() ?: 'The Gospel According to St. Matthew';

    $chapterStmt = $db->prepare("SELECT value FROM settings WHERE key = 'currently_reading_chapter'");
    $chapterStmt->execute();
    $currentChapter = $chapterStmt->fetchColumn() ?: 'Chapter 1, Verses 18–25';

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
    $currentChapter = 'Chapter 1, Verses 18–25';
    $currentTopic = '';
    $quoteText = '';
    $quoteSource = '';
}
?>

    <!-- ======== WELCOME MESSAGE ======== -->
    <section class="welcome-banner">
        <p>Thank you for coming!! Everyone is welcome, whether you have done the reading or not!</p>
    </section>

    <!-- ======== ANNOUNCEMENTS ======== -->
    <section class="announcements">
        <h2>Announcements</h2>
        <p>
            Our third meeting will be two weeks after Easter &mdash; April 19th &mdash;
            at the church basement! The reading assignment is
            <strong>Matthew 2:1&ndash;23, pages 62&ndash;86</strong> (25 pages).
            Please bring any favorite quotes, questions and thoughts.
            We will be doing the rosary, so please, also bring your prayer intentions!
        </p>
    </section>

    <!-- ======== FIVE-PART QUESTIONNAIRE ======== -->
    <section class="questionnaire">
        <h2>Five-Part Questionnaire</h2>
        <p class="questionnaire-note">
            These questions are meant to be helpful, not to pressure anyone!
            Use them if they spark something for you.
        </p>
        <ol class="discussion-questions">
            <li>Which Church Father's interpretation struck you most? Why?</li>
            <li>What does this passage reveal about Christ?</li>
            <li>What did the commentary illuminate that you hadn't noticed in the text before?</li>
            <li>What practical application does this have for our lives this week?</li>
            <li>What questions remain after reading?</li>
        </ol>
    </section>

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

    <!-- ======== CATENA APP RECOMMENDATION ======== -->
    <section class="catena-app-section">
        <h2>Free Bible Study App</h2>
        <div class="catena-app-content">
            <a href="https://catenabible.com" target="_blank" class="catena-app-icon-link">
                <img src="/images/catena-app-icon.png" alt="Catena Bible App" class="catena-app-icon">
            </a>
            <p>
                If you don't already have the free Catena Bible study app, this is an
                amazing way to read the Fathers &mdash; and far beyond them chronologically!
                All in one place. Only downside is typos and sometimes no exact citations.
                It has a lot of the same commentary as the Catena Aurea but more, and
                sometimes much more in depth and understandable.
            </p>
            <p>
                Please feel free to browse the app and find commentary to bring to the
                table for discussion! Any amount of reading/supplementary material is
                welcome. We know you are busy!
            </p>
            <div class="catena-app-links">
                <a href="https://apps.apple.com/us/app/catena-bible-commentaries/id1218663640" target="_blank">Download for iPhone/iPad</a>
                <span class="sep">&middot;</span>
                <a href="https://play.google.com/store/apps/details?id=com.catena&hl=en_US" target="_blank">Download for Android</a>
                <span class="sep">&middot;</span>
                <a href="https://catenabible.com" target="_blank">Visit Website</a>
            </div>
        </div>
    </section>

    <!-- ======== LAPIDE.ORG ======== -->
    <section class="lapide-section">
        <h2>Cornelius a Lapide Commentary</h2>
        <div class="lapide-content">
            <p>
                Fran has been working on translating the commentaries of
                <strong>Cornelius a Lapide</strong>, one of the great Catholic
                biblical commentators.
            </p>
            <a href="https://lapide.org" target="_blank" class="lapide-link">Visit Lapide.org &rarr;</a>
        </div>
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
