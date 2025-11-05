<?php

/**
 * Username Validation for Registration
 *
 * Validates username meets requirements and is available
 */

/**
 * Validate registration username
 *
 * @param string $username Username to validate
 * @return string|null Error message if invalid, null if valid
 */
function validate_registration_username($username)
{
    if (empty($username)) {
        return "Username is required.";
    }

    if (strlen($username) < 3) {
        return "Username must be at least 3 characters.";
    }

    // WordPress validation (SQL safety, reserved words) AND stricter pattern (alphanumeric + underscore only)
    if (!validate_username($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Username contains invalid characters.";
    }

    if (username_exists($username)) {
        return "This username is already taken.";
    }

    return null;
}
