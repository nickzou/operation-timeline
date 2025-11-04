<?php

/**
 * Confirm Password Validation for Registration
 *
 * Validates password confirmation matches
 */

/**
 * Validate registration password confirmation
 *
 * @param string $password Original password
 * @param string $confirm_password Confirmation password
 * @return string|null Error message if invalid, null if valid
 */
function validate_registration_confirm_password($password, $confirm_password)
{
    if (empty($confirm_password)) {
        return "Please confirm your password.";
    }

    if ($password !== $confirm_password) {
        return "Passwords do not match.";
    }

    return null;
}
