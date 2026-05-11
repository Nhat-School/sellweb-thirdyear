<?php

function set_flash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function flash_class(string $type): string {
    return match($type) {
        'success' => 'success',
        'error'   => 'danger',
        'warning' => 'warning',
        default   => 'info',
    };
}

function flash_icon(string $type): string {
    return match($type) {
        'success' => 'fa-check-circle',
        'error'   => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        default   => 'fa-info-circle',
    };
}
?>
