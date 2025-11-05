<?php

/**
 * Email Validation for Registration
 *
 * Validates email format and availability
 */

/**
 * Validate registration email
 *
 * @param string $email Email to validate
 * @return string|null Error message if invalid, null if valid
 */
function validate_registration_email($email)
{
    if (empty($email)) {
        return "Email is required.";
    }

    if (!is_email($email)) {
        return "Please enter a valid email address.";
    }

    if (email_exists($email)) {
        return "This email is already registered.";
    }

    return null;
}
