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
} catch (Exception $e) {
    $currentBook = 'The Gospel According to St. Matthew';
    $currentChapter = 'Chapter 1, Verses 1–17';
}
?>

    <!-- ======== CURRENTLY READING ======== -->
    <section class="currently-reading">
        <p class="currently-reading-label">Currently Reading</p>
        <p class="currently-reading-title"><?= e($currentBook) ?></p>
        <p class="currently-reading-chapter"><?= e($currentChapter) ?></p>
        <p class="currently-reading-pace">Please read at your own pace!</p>
        <a href="https://a.co/d/07pyCbSM" class="book-cover-link" target="_blank">
            <img src="/images/catena-aurea-cover.jpg" alt="Catena Aurea, Volume 1 — available on Amazon" class="book-cover">
        </a>
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
