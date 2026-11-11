<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Messages';
$loginError = '';
$sessionToken = $_SESSION['messages_csrf_token'] ?? null;
if (!is_string($sessionToken)) {
    $sessionToken = bin2hex(random_bytes(32));
    $_SESSION['messages_csrf_token'] = $sessionToken;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? null;
    if (!is_string($postedToken) || !hash_equals($sessionToken, $postedToken)) {
        $loginError = 'Your session expired. Please reload the page and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        if ($action === 'login') {
            $password = $_POST['password'] ?? null;
            if (is_string($password) && hash_equals($messagesPassword, $password)) {
                session_regenerate_id(true);
                $_SESSION['messages_authenticated'] = true;
                $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
                header('Location: messages.php', true, 303);
                exit;
            }

            $loginError = 'That password was not correct. Please try again.';
        } elseif ($action === 'logout' && ($_SESSION['messages_authenticated'] ?? false) === true) {
            unset($_SESSION['messages_authenticated']);
            $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
            header('Location: messages.php', true, 303);
            exit;
        } else {
            $loginError = 'That request could not be completed. Please try again.';
        }
    }
}

$isAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;
$submissions = [];

if ($isAuthenticated) {
    $messagesPath = __DIR__ . '/storage/messages.jsonl';
    if (is_file($messagesPath)) {
        $lines = file($messagesPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new RuntimeException('Unable to read stored contact messages.');
        }

        foreach ($lines as $line) {
            $submission = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($submission)) {
                throw new UnexpectedValueException('A stored contact message has an invalid format.');
            }

            foreach (['timestamp', 'name', 'email', 'phone', 'service', 'message'] as $field) {
                if (!isset($submission[$field]) || !is_string($submission[$field])) {
                    throw new UnexpectedValueException('A stored contact message is missing a valid ' . $field . ' field.');
                }
            }

            $submissions[] = $submission;
        }
    }

    $submissions = array_reverse($submissions);
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-content">
        <p class="eyebrow">Private area</p>
        <h1>Contact <span>messages.</span></h1>
        <p>Submissions sent through the Ola &amp; Son contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$isAuthenticated): ?>
            <div class="messages-login">
                <p class="eyebrow">Password required</p>
                <h2>View submissions</h2>
                <p>Enter the local learning password to continue.</p>
                <?php if ($loginError !== ''): ?>
                    <p class="form-error-summary" role="alert"><?= e($loginError) ?></p>
                <?php endif; ?>
                <form class="messages-login-form" method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($sessionToken) ?>">
                    <input type="hidden" name="action" value="login">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button button-dark" type="submit">Continue <span aria-hidden="true">&rarr;</span></button>
                </form>
            </div>
        <?php else: ?>
            <div class="messages-heading">
                <div>
                    <p class="eyebrow">Inbox</p>
                    <h2>Contact submissions <span class="count-badge"><?= e((string) count($submissions)) ?></span></h2>
                </div>
                <form method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($sessionToken) ?>">
                    <input type="hidden" name="action" value="logout">
                    <button class="button button-dark" type="submit">Sign out</button>
                </form>
            </div>

            <?php if ($submissions === []): ?>
                <p class="messages-empty">There are no messages yet.</p>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td><?= e($submission['timestamp']) ?></td>
                                    <td><?= e($submission['name']) ?></td>
                                    <td><a href="mailto:<?= e($submission['email']) ?>"><?= e($submission['email']) ?></a></td>
                                    <td><?= e($submission['phone']) ?></td>
                                    <td><?= e($submission['service']) ?></td>
                                    <td class="message-cell"><?= e($submission['message']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
