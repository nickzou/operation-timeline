<?php

/**
 * Terms Validation for Registration
 *
 * Validates terms and conditions acceptance
 */

/**
 * Validate registration terms acceptance
 *
 * @param bool $terms Whether terms were accepted
 * @return string|null Error message if invalid, null if valid
 */
function validate_registration_terms($terms)
{
    if (!$terms) {
        return "You must agree to the Terms and Conditions to register.";
    }

    return null;
}
