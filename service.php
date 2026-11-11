<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$requestedSlug = $_GET['slug'] ?? '';
$service = null;

if (is_string($requestedSlug)) {
    foreach ($services as $candidate) {
        if ($candidate['slug'] === $requestedSlug) {
            $service = $candidate;
            break;
        }
    }
}

if ($service === null) {
    http_response_code(404);
    $pageTitle = 'Service not found';
} else {
    $pageTitle = $service['title'];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($service === null): ?>
    <section class="page-hero">
        <div class="container page-hero-content">
            <p class="eyebrow">Page not found</p>
            <h1>We couldn't find <span>that service.</span></h1>
            <p>It may have moved or the link may be incorrect. Browse our services to find the support you need.</p>
            <a class="button button-light" href="services.php">Browse our services <span aria-hidden="true">&rarr;</span></a>
        </div>
    </section>
<?php else: ?>
    <section class="page-hero">
        <div class="container page-hero-content">
            <p class="eyebrow">How we can help</p>
            <h1><?= e($service['title']) ?></h1>
            <p><?= e($service['description']) ?></p>
        </div>
    </section>
    <section class="section">
        <div class="container service-detail">
            <span class="service-icon service-detail-icon" aria-hidden="true"><?= e($service['icon']) ?></span>
            <div>
                <p class="eyebrow">Investment</p>
                <h2><?= e(formatPrice($service['price'])) ?></h2>
                <p>Pricing shown is a starting point. Get in touch and we can discuss the scope and approach that best fit your needs.</p>
                <a class="button button-dark" href="contact.php">Ask us about this service <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
