<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="/style.css">
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
            <img src="/images/aquinas-portrait.jpg" alt="St. Thomas Aquinas">
        </div>
        <h1>St. Joseph's Church<br><span style="margin-right: -0.05em">C</span>atena Aurea Reading Group</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>
    </header>

    <!-- ======== NAVIGATION ======== -->
    <nav class="site-nav">
        <a href="/"<?= isActive('home') ?>>Home</a>
        <span class="sep">&#9830;</span>
        <a href="/resources"<?= isActive('resources') ?>>Resources</a>
        <span class="sep">&#9830;</span>
        <a href="/community"<?= isActive('community') ?>>Community</a>
    </nav>

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

</body>
</html>
