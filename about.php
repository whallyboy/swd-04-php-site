<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">A little about us</p>
        <h1>Good business is <span>personal.</span></h1>
        <p>We are an independent team built around a simple idea: businesses do their best work when they have people they can count on.</p>
    </div>
</section>

<section class="section">
    <div class="container story-layout">
        <div class="story-label">
            <p class="eyebrow">Our story</p>
            <span class="story-initials" aria-hidden="true">O&amp;S</span>
        </div>
        <div class="story-copy">
            <h2>Rooted in relationships.<br><span>Ready for what's next.</span></h2>
            <p>Ola &amp; Son Ltd was founded to offer businesses a more personal kind of professional support. We pair years of practical experience with the curiosity and care to understand every client's individual goals.</p>
            <p>There is no one-size-fits-all playbook here. We listen first, work alongside your team and shape our approach around what will make a real difference to you.</p>
            <p>Whether you are taking your first steps or planning your next stage of growth, we are here to help you make it happen with confidence.</p>
        </div>
    </div>
</section>

<section class="section team-section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">The people behind the work</p>
            <h2>A small team with <span>big care.</span></h2>
            <p>Meet the people who bring curiosity, experience and a personal touch to every partnership.</p>
        </div>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <article class="team-card">
                    <span class="team-initials" aria-hidden="true"><?= e(substr($member['name'], 0, 1)) ?></span>
                    <h3><?= e($member['name']) ?></h3>
                    <p class="team-role"><?= e($member['role']) ?></p>
                    <p><?= e($member['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="values-band">
    <div class="container values-inner">
        <p class="eyebrow">What we believe</p>
        <h2>Trust is earned in the <span>details.</span></h2>
        <div class="values-list">
            <p><strong>Listen carefully.</strong> The right answer starts with the right questions.</p>
            <p><strong>Do what we say.</strong> Clear commitments and dependable follow-through matter.</p>
            <p><strong>Keep it human.</strong> Respect, openness and good communication go a long way.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
