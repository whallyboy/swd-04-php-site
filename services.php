<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">How we can help</p>
        <h1>Useful support for <span>what's next.</span></h1>
        <p>Thoughtful, practical services to help your business get clarity, build momentum and do its best work.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="service-grid">
            <?php foreach ($services as $service): ?>
                <?= renderServiceCard($service) ?>
            <?php endforeach; ?>
        </div>
        <div class="services-note">
            <p>Not sure which service fits? <a href="contact.php">Get in touch</a> and we can work it out together.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
