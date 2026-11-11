<?php
declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Independent expertise. Personal service.</p>
        <h1>Good work starts with <span>good people.</span></h1>
        <p class="hero-intro">We help ambitious businesses move forward with clear advice, dependable support and a partner who knows your name.</p>
        <a class="button button-light" href="services.php">Explore our services <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
    <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">A better way to do business</p>
            <h2>Small enough to care.<br><span>Experienced enough to deliver.</span></h2>
            <p>At Ola &amp; Son, good relationships are at the heart of good results. We bring practical thinking and hands-on experience to the challenges that matter to your business.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-number">01</span>
                <h3>People first</h3>
                <p>You work directly with the people doing the work: attentive, approachable and invested in your success.</p>
            </article>
            <article class="feature-card">
                <span class="feature-number">02</span>
                <h3>Clear thinking</h3>
                <p>We make complex decisions easier with honest advice, straightforward plans and no unnecessary jargon.</p>
            </article>
            <article class="feature-card">
                <span class="feature-number">03</span>
                <h3>Built to last</h3>
                <p>We focus on dependable outcomes and partnerships that keep delivering long after the first project.</p>
            </article>
        </div>
    </div>
</section>

<section class="callout">
    <div class="container callout-inner">
        <div>
            <p class="eyebrow">Let's get to know each other</p>
            <h2>Have a challenge in mind?</h2>
            <p>Tell us what you are working on. We would be glad to talk it through.</p>
        </div>
        <a class="button button-dark" href="contact.php">Start a conversation <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
