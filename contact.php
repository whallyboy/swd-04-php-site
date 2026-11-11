<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Contact';
$fields = ['name', 'email', 'phone', 'service', 'message'];
$values = array_fill_keys($fields, '');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field) {
        $input = $_POST[$field] ?? '';

        if (!is_string($input)) {
            $errors[$field] = 'Please enter a valid value.';
            continue;
        }

        $values[$field] = trim($input);
    }

    $postedToken = $_POST['csrf_token'] ?? null;
    $sessionToken = $_SESSION['contact_csrf_token'] ?? null;
    if (
        !is_string($postedToken)
        || !is_string($sessionToken)
        || !hash_equals($sessionToken, $postedToken)
    ) {
        $errors['csrf'] = 'Your form session has expired. Please try again.';
    }

    if ($values['name'] === '' && !isset($errors['name'])) {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '' && !isset($errors['email'])) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!isset($errors['email']) && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '' && !isset($errors['service'])) {
        $errors['service'] = 'Please choose a service.';
    } elseif (
        !isset($errors['service'])
        && !in_array($values['service'], array_column($services, 'slug'), true)
    ) {
        $errors['service'] = 'Please choose a valid service.';
    }

    if ($values['message'] === '' && !isset($errors['message'])) {
        $errors['message'] = 'Please enter a message.';
    } elseif (!isset($errors['message'])) {
        $messageLength = mb_strlen($values['message'], 'UTF-8');
        if ($messageLength < 10 || $messageLength > 1000) {
            $errors['message'] = 'Your message must be between 10 and 1,000 characters.';
        }
    }

    if ($errors === []) {
        $storageDirectory = __DIR__ . '/storage';
        if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0755, true) && !is_dir($storageDirectory)) {
            throw new RuntimeException('Unable to create the contact message storage directory.');
        }

        $submission = [
            'timestamp' => date(DATE_ATOM),
            'name' => $values['name'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'service' => $values['service'],
            'message' => $values['message'],
        ];
        $jsonLine = json_encode($submission, JSON_THROW_ON_ERROR) . PHP_EOL;
        if (file_put_contents($storageDirectory . '/messages.jsonl', $jsonLine, FILE_APPEND | LOCK_EX) === false) {
            throw new RuntimeException('Unable to save the contact message.');
        }

        unset($_SESSION['contact_csrf_token']);
        header('Location: contact.php?sent=1', true, 303);
        exit;
    }
}

if (!isset($_SESSION['contact_csrf_token'])) {
    $_SESSION['contact_csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">Let's talk</p>
        <h1>Your next step starts <span>with a conversation.</span></h1>
        <p>Tell us a little about what you need. We will be happy to listen and explore how we might help.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-layout">
        <div class="contact-copy">
            <p class="eyebrow">Get in touch</p>
            <h2>Good conversations<br><span>make things happen.</span></h2>
            <p>Whether you have a clear brief or just a question, reach out using the form. You can also email us at <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>.</p>
        </div>
        <form class="contact-form" method="post" action="contact.php" novalidate>
            <?php if (($_GET['sent'] ?? '') === '1'): ?>
                <p class="form-success" role="status">Thanks for getting in touch. Your message has been received.</p>
            <?php endif; ?>

            <?php if (isset($errors['csrf'])): ?>
                <p class="form-error-summary" role="alert"><?= e($errors['csrf']) ?></p>
            <?php endif; ?>

            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['contact_csrf_token']) ?>">

            <div class="form-field">
                <label for="name">Name <span aria-hidden="true">*</span></label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    autocomplete="name"
                    required
                    value="<?= e($values['name']) ?>"
                    <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>
                >
                <?php if (isset($errors['name'])): ?>
                    <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="email">Email <span aria-hidden="true">*</span></label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    value="<?= e($values['email']) ?>"
                    <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="phone">Phone <span class="optional">(optional)</span></label>
                <input
                    id="phone"
                    name="phone"
                    type="tel"
                    autocomplete="tel"
                    value="<?= e($values['phone']) ?>"
                    <?= isset($errors['phone']) ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?>
                >
                <?php if (isset($errors['phone'])): ?>
                    <p class="field-error" id="phone-error"><?= e($errors['phone']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="service">Service <span aria-hidden="true">*</span></label>
                <select
                    id="service"
                    name="service"
                    required
                    <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>
                >
                    <option value="">Choose a service</option>
                    <?php foreach ($services as $service): ?>
                        <option
                            value="<?= e($service['slug']) ?>"
                            <?= $values['service'] === $service['slug'] ? 'selected' : '' ?>
                        ><?= e($service['title']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['service'])): ?>
                    <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="message">Message <span aria-hidden="true">*</span></label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    required
                    aria-describedby="<?= isset($errors['message']) ? 'message-error message-hint' : 'message-hint' ?>"
                    <?= isset($errors['message']) ? 'aria-invalid="true"' : '' ?>
                ><?= e($values['message']) ?></textarea>
                <p class="field-hint" id="message-hint">Please use 10 to 1,000 characters.</p>
                <?php if (isset($errors['message'])): ?>
                    <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                <?php endif; ?>
            </div>

            <button class="button button-dark" type="submit">Send message <span aria-hidden="true">&rarr;</span></button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
