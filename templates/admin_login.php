<?php if (isAdmin()):
    // Fetch current quote of the week for the form
    try {
        $db = getDb();
        $qtStmt = $db->prepare("SELECT value FROM settings WHERE key = 'quote_of_the_week_text'");
        $qtStmt->execute();
        $currentQuote = $qtStmt->fetchColumn() ?: '';
        $qsStmt = $db->prepare("SELECT value FROM settings WHERE key = 'quote_of_the_week_source'");
        $qsStmt->execute();
        $currentQuoteSource = $qsStmt->fetchColumn() ?: '';
    } catch (Exception $e) {
        $currentQuote = '';
        $currentQuoteSource = '';
    }
?>
    <div class="page-content">
        <h1 class="page-title">Admin</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <p class="text-center">
            Logged in as <strong><?= e(adminName()) ?></strong>
        </p>

        <div class="admin-actions">
            <p><a href="/admin/password">Change Password</a></p>
            <p><a href="/admin/logout">Log Out</a></p>
        </div>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <p class="text-center" style="color: #c4a95a;">
            While logged in, you'll see <span style="color: #c0392b;">&#10005; Delete</span>
            buttons on posts and comments across the site.
        </p>

        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <!-- Quote of the Week editor -->
        <h2>Quote of the Week</h2>
        <p style="color: #c4a95a; font-size: 0.95rem;">
            Set a highlighted quote that appears on the homepage. Leave the text blank to hide it.
        </p>

        <?php if (isset($_GET['quote_saved'])): ?>
            <p class="notice" style="color: #d4b85a;">Quote of the Week updated!</p>
        <?php endif; ?>

        <form action="/admin/quote/save" method="POST" class="post-form" style="max-width: 600px; margin: 1rem auto;">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="form-group">
                <label for="quote_text">Quote Text</label>
                <textarea id="quote_text" name="quote_text" rows="4" placeholder="Enter a verse, quote, or comment..."><?= e($currentQuote) ?></textarea>
            </div>
            <div class="form-group">
                <label for="quote_source">Source (optional)</label>
                <input type="text" id="quote_source" name="quote_source" placeholder="e.g. St. John Chrysostom, Homily 1" value="<?= e($currentQuoteSource) ?>">
            </div>
            <button type="submit" class="btn">Save Quote</button>
        </form>
    </div>

<?php else: ?>
    <div class="page-content">
        <h1 class="page-title">Admin Login</h1>
        <div class="ornament">&#10022; &#9670; &#10022;</div>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice">Invalid username or password. Please try again.</p>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="post-form" style="max-width: 400px; margin: 1.5rem auto;">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
    </div>
<?php endif; ?>
