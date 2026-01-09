<?php

function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_input(): void {
    $t = generate_csrf_token();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf_token(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) return false;
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    // invalidate token after check to prevent replay
    unset($_SESSION['csrf_token']);
    return $valid;
}
