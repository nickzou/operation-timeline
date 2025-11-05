<?php

/**
 * Common Password Detection
 *
 * Checks if a password is in a list of commonly used passwords
 */

/**
 * Check if password is too common
 *
 * @param string $password Password to check
 * @return bool True if password is too common
 */
function is_password_too_common($password)
{
    $common_passwords = [
        "password",
        "password123",
        "Password123!",
        "12345678",
        "qwerty",
        "abc123",
        "monkey",
        "letmein",
        "trustno1",
        "dragon",
        "baseball",
        "iloveyou",
        "master",
        "sunshine",
        "ashley",
        "bailey",
        "passw0rd",
        "shadow",
        "123123",
        "654321",
        "superman",
        "qazwsx",
        "michael",
        "football",
    ];

    return in_array($password, $common_passwords);
}
