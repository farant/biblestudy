<?php
/**
 * Discussion Answers page — group members can share their responses
 * to the discussion questions for each chapter.
 */

$chapters = require __DIR__ . '/../config/chapters.php';
$questions = require __DIR__ . '/../config/questions.php';
$selectedSlug = $_GET['chapter'] ?? '';

// Default to the most recent chapter
if (!$selectedSlug && !empty($chapters)) {
    $selectedSlug = end($chapters)['slug'];
}

// Find the selected chapter info
$selectedChapter = null;
foreach ($chapters as $ch) {
    if ($ch['slug'] === $selectedSlug) {
        $selectedChapter = $ch;
        break;
    }
}

// Load all answers for the selected chapter
$allAnswers = [];
if ($selectedChapter) {
    $db = getDb();
    $stmt = $db->prepare('SELECT * FROM discussion_answers WHERE chapter_slug = ? ORDER BY created_at');
    $stmt->execute([$selectedSlug]);
    $allAnswers = $stmt->fetchAll();
}

// Check for edit data loaded from session
$editData = $_SESSION['edit_answers'] ?? null;
unset($_SESSION['edit_answers']);

// Pre-fill answers by index position when editing
$prefill = [];
if ($editData && !empty($editData['responses'])) {
    foreach ($editData['responses'] as $i => $r) {
        $prefill[$i] = $r['answer'] ?? '';
    }
}
$prefillName = $editData['author_name'] ?? '';
?>

<div class="page-content">
    <h1 class="page-title">Discussion Answers</h1>
    <p class="page-subtitle">Share your thoughts on each chapter&rsquo;s reading</p>

    <!-- Chapter selector -->
    <form class="chapter-selector" method="get" action="/discussions">
        <label for="chapter">Select a chapter:</label>
        <select name="chapter" id="chapter" onchange="this.form.submit()">
            <?php foreach ($chapters as $ch): ?>
                <option value="<?= e($ch['slug']) ?>"<?= $ch['slug'] === $selectedSlug ? ' selected' : '' ?>>
                    <?= e($ch['label']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit" class="btn btn-small">View</button></noscript>
    </form>

    <!-- Status messages -->
    <?php if (isset($_GET['saved'])): ?>
        <div class="discussion-message success">Your answers have been saved!</div>
    <?php endif; ?>
    <?php if (isset($_GET['editing'])): ?>
        <div class="discussion-message info">Your answers have been loaded below. Make your changes and save.</div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'missing_fields'): ?>
        <div class="discussion-message error">Please fill in your name and password.</div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'wrong_password'): ?>
        <div class="discussion-message error">That password doesn&rsquo;t match the one on file for that name. If this is your first time posting, someone else may have already used that name.</div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'not_found'): ?>
        <div class="discussion-message error">No saved answers found with that name and password for this chapter.</div>
    <?php endif; ?>

    <?php if ($selectedChapter): ?>

    <!-- Submit / Edit form -->
    <div class="post-form-container">
        <details class="post-form-toggle"<?= $editData ? ' open' : '' ?>>
            <summary>Share Your Answers for <?= e($selectedChapter['label']) ?></summary>
            <div class="post-form">

                <!-- Load existing answers for editing -->
                <?php if (!$editData): ?>
                <div class="load-answers-box">
                    <p class="load-answers-label">Coming back to edit? Load your previous answers:</p>
                    <form method="post" action="/discussions/load" class="load-answers-form">
                        <input type="hidden" name="chapter_slug" value="<?= e($selectedSlug) ?>">
                        <input type="text" name="author_name" placeholder="Your name" required class="load-input">
                        <input type="password" name="author_password" placeholder="Your password" required class="load-input">
                        <button type="submit" class="btn btn-small">Load My Answers</button>
                    </form>
                </div>
                <div class="form-divider">
                    <span>or submit new answers</span>
                </div>
                <?php endif; ?>

                <!-- Main answer form -->
                <form method="post" action="/discussions/save">
                    <input type="hidden" name="chapter_slug" value="<?= e($selectedSlug) ?>">

                    <!-- Honeypot -->
                    <div class="hp-field">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="author_name">Your Name</label>
                        <input type="text" name="author_name" id="author_name" required
                               placeholder="e.g. Amanda"
                               value="<?= e($prefillName) ?>">
                    </div>

                    <div class="form-group">
                        <label for="author_password">Your Password</label>
                        <input type="password" name="author_password" id="author_password" required
                               placeholder="A word you'll remember">
                        <p class="field-help">Pick something simple you&rsquo;ll remember &mdash; this lets you come back and edit your answers later.</p>
                    </div>

                    <?php foreach ($questions as $i => $q): ?>
                    <div class="form-group">
                        <label for="answer_<?= $i ?>"><?= ($i + 1) ?>. <?= e($q) ?></label>
                        <input type="hidden" name="questions[<?= $i ?>]" value="<?= e($q) ?>">
                        <textarea name="answers[<?= $i ?>]" id="answer_<?= $i ?>" rows="3"
                                  placeholder="Your thoughts..."><?= e($prefill[$i] ?? '') ?></textarea>
                    </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn">Save My Answers</button>
                </form>
            </div>
        </details>
    </div>

    <!-- Display everyone's answers -->
    <?php if (empty($allAnswers)): ?>
        <p class="no-answers">No one has shared answers for <?= e($selectedChapter['label']) ?> yet. Be the first!</p>
    <?php else: ?>
        <h2><?= e($selectedChapter['label']) ?> &mdash; What the Group Shared</h2>
        <?php foreach ($allAnswers as $a): ?>
        <div class="answer-card">
            <div class="answer-card-header">
                <span class="answer-author"><?= e($a['author_name']) ?></span>
                <span class="answer-date"><?= formatDate($a['updated_at']) ?></span>
            </div>
            <div class="answer-card-body">
                <?php
                $responses = json_decode($a['responses'], true);
                foreach ($responses as $i => $r):
                    if (!empty($r['answer'])):
                ?>
                <div class="answer-item">
                    <p class="answer-question"><?= ($i + 1) ?>. <?= e($r['question']) ?></p>
                    <p class="answer-text"><?= nl2br(e($r['answer'])) ?></p>
                </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
            <?php if (isAdmin()): ?>
            <div class="delete-form">
                <form method="post" action="/discussions/delete" style="display:inline" onsubmit="return confirm('Delete this entry?')">
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="answer_id" value="<?= (int)$a['id'] ?>">
                    <input type="hidden" name="chapter_slug" value="<?= e($selectedSlug) ?>">
                    <button type="submit" class="btn-delete">Delete</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php endif; ?>
</div>
