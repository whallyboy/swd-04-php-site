# Ola & Son Ltd

A fictional business website built while learning PHP 8. It demonstrates reusable page includes, data-driven services and team content, server-side form validation, and simple protected access to locally stored contact submissions.

## Features

- Shared header, navigation, and footer across multiple pages
- Responsive Home, About, Services, Service Detail, and Contact pages
- Data-driven service cards and team profiles
- Contact form with server-side validation, sticky values, CSRF token, and Post/Redirect/Get
- Contact submissions stored as JSON Lines in `storage/messages.jsonl`
- Password-protected messages viewer with newest submissions first
- Escaped dynamic output to reduce cross-site scripting risk

## Tech stack

- PHP 8 (strict types and built-in session, validation, and JSON functions)
- HTML5
- CSS3 with responsive layouts
- Apache through XAMPP
- JSON Lines for local contact-message storage

## Run with XAMPP

1. Install and start **Apache** from the XAMPP Control Panel.
2. Keep the project in this exact folder:

   ```text
   C:\xampp\htdocs\swd\hp-04-php-site
   ```

3. Visit the site in a browser:

   [http://localhost/swd/hp-04-php-site/](http://localhost/swd/hp-04-php-site/)

4. Submit a test message through the Contact page. PHP creates `storage/messages.jsonl` when the first message is received.
5. To view submissions, visit [http://localhost/swd/hp-04-php-site/messages.php](http://localhost/swd/hp-04-php-site/messages.php). The learning password is defined as `$messagesPassword` in `includes/config.php`; change it before use.

## Project structure

```text
hp-04-php-site/
|-- about.php
|-- contact.php
|-- index.php
|-- messages.php
|-- service.php
|-- services.php
|-- README.md
|-- assets/
|   `-- css/
|       `-- style.css
|-- includes/
|   |-- config.php
|   |-- data.php
|   |-- footer.php
|   |-- functions.php
|   `-- header.php
`-- storage/
    |-- .htaccess
    `-- messages.jsonl  (created after the first form submission)
```

## What I learned

- What I learned about PHP includes and `require_once`: ______________________________
- What I learned about arrays, loops, and reusable functions: _________________________
- What I learned about validating and safely displaying form data: ____________________
- What I learned about sessions, CSRF tokens, and redirects: __________________________
- What I would like to explore next: _________________________________________________

## Local-learning security note

The messages page is protected by a hard-coded password in `includes/config.php` because this is a local learning project. It is not production-grade: the password is stored in source code, there is no user/account management or rate limiting, and the submissions are plain-text files on disk. Do not deploy this setup with real personal data. A production system should use managed credentials or securely hashed passwords, HTTPS, stronger access controls, protected storage outside the web root, and appropriate operational safeguards.
