<?php

/**
 * Password Validation for Registration
 *
 * Validates password meets complexity requirements
 */

// Load password validation functions
require_once __DIR__ . '/is_password_too_common.php';

/**
 * Validate registration password
 *
 * @param string $password Password to validate
 * @return string|null Error message if invalid, null if valid
 */
function validate_registration_password($password)
{
    if (empty($password)) {
        return "Password is required.";
    }

    if (strlen($password) < 8) {
        return "Password must be at least 8 characters.";
    }

    if (strlen($password) > 64) {
        return "Password must not exceed 64 characters.";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return "Password must contain at least one uppercase letter.";
    }

    if (!preg_match("/[a-z]/", $password)) {
        return "Password must contain at least one lowercase letter.";
    }

    if (!preg_match("/[0-9]/", $password)) {
        return "Password must contain at least one number.";
    }

    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return "Password must contain at least one special character (!@#$%^&*(),.?\":{}|<>).";
    }

    if (is_password_too_common($password)) {
        return "This password is too common. Please choose a more secure password.";
    }

    return null;
}
