<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="/style.css?v=2">
</head>
<body>

<!-- The whole site sits inside this "page-frame" which creates the
     illuminated manuscript border effect on the dark background -->
<div class="page-frame">
<div class="page-inner">

    <!-- ======== HEADER ======== -->
    <header class="site-header">
        <div class="header-icon-left">
            <img src="/images/quill.svg" alt="Quill">
        </div>
        <div class="header-icon-right">
            <img src="/images/inkwell.svg" alt="Inkwell">
        </div>
        <div class="header-portrait">
            <img src="/images/aquinas-portrait.jpg" alt="St. Thomas Aquinas by Fra Angelico">
        </div>
        <p class="portrait-attribution">St. Thomas Aquinas by Fra Angelico</p>
        <h1>St. Joseph's Church<br><span style="margin-right: -0.05em">C</span>atena Aurea Reading Group</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>
    </header>

    <!-- ======== NAVIGATION ======== -->
    <nav class="site-nav">
        <a href="/"<?= isActive('home') ?>>Home</a>
        <span class="sep">&#9830;</span>
        <a href="/resources"<?= isActive('resources') ?>>Resources</a>
        <span class="sep">&#9830;</span>
        <a href="/prayers"<?= isActive('prayers') ?>>Prayer Intentions</a>
        <span class="sep">&#9830;</span>
        <a href="/community"<?= isActive('community') ?>>Community</a>
    </nav>

    <?php if (isAdmin()): ?>
    <div class="admin-bar">
        Logged in as <?= e(adminName()) ?>
        &middot; <a href="/admin">Admin</a>
        &middot; <a href="/admin/logout">Log Out</a>
    </div>
    <?php endif; ?>

    <!-- ======== PAGE CONTENT ======== -->
    <?php require __DIR__ . "/{$page}.php"; ?>

    <!-- ======== FOOTER ======== -->
    <footer class="site-footer">
        <div class="footer-ornament">&#10022; &#9670; &#10022;</div>
        <p class="footer-verse">
            "Wherefore I wished, and understanding was given me: and I called
            upon God, and the spirit of wisdom came upon me. And I preferred
            her before kingdoms and thrones, and esteemed riches nothing in
            comparison of her. Neither did I compare unto her any precious
            stone: for all gold in comparison of her, is but a little sand,
            and silver in respect to her shall be counted as clay. I loved
            her above health and beauty, and chose to have her instead of
            light: for her light cannot be put out."
        </p>
        <p class="footer-cite">&mdash; Wisdom 7:7&ndash;10 (Douay-Rheims)</p>
    </footer>

</div><!-- end page-inner -->
</div><!-- end page-frame -->

<!-- Lightbox for full-size images -->
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="Full size image">
</div>
<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.getElementById('lightbox-img').src = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>

</body>
</html>
