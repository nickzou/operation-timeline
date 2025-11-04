<?php

/**
 * Username Validation Function Tests
 *
 * Tests for validate_registration_username() function
 *
 * Note: Tests that check username_exists() are skipped as they require WordPress database.
 * We only test the validation logic that doesn't depend on database state.
 */

// Load the validation function
require_once __DIR__ .
    "/../../web/wp-content/themes/operation-timeline/inc/user_registration/validate_registration_username.php";

describe("validate_registration_username", function () {
    test("returns error for empty username", function () {
        $result = validate_registration_username("");
        expect($result)->toBe("Username is required.");
    });

    test("returns error for username less than 3 characters", function () {
        $result = validate_registration_username("ab");
        expect($result)->toBe("Username must be at least 3 characters.");
    });

    test("returns error for username with exactly 2 characters", function () {
        $result = validate_registration_username("ab");
        expect($result)->toBe("Username must be at least 3 characters.");
    });

    test("returns error for username with single character", function () {
        $result = validate_registration_username("a");
        expect($result)->toBe("Username must be at least 3 characters.");
    });

    test("returns error for username with space", function () {
        $result = validate_registration_username("user name");
        expect($result)->toBe("Username contains invalid characters.");
    });

    test("returns error for username with @ symbol", function () {
        $result = validate_registration_username("user@name");
        expect($result)->toBe("Username contains invalid characters.");
    });

    test("returns error for username with hyphen", function () {
        $result = validate_registration_username("user-name");
        expect($result)->toBe("Username contains invalid characters.");
    });

    test("returns error for username with period", function () {
        $result = validate_registration_username("user.name");
        expect($result)->toBe("Username contains invalid characters.");
    });

    test("returns error for username with special characters", function () {
        $invalidChars = [
            "!",
            "#",
            '$',
            "%",
            "^",
            "&",
            "*",
            "(",
            ")",
            "+",
            "=",
            "[",
            "]",
            "{",
            "}",
            "|",
            "\\",
            "/",
            "<",
            ">",
            ",",
            ".",
            "?",
        ];

        foreach ($invalidChars as $char) {
            $username = "user{$char}name";
            $result = validate_registration_username($username);
            expect($result)->toBe("Username contains invalid characters.");
        }
    });

    // Note: We can't test if username passes all validation without mocking username_exists()
    // These tests verify format validation only - the function may still return "already taken" error
});
