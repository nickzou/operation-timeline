<?php

/**
 * Confirm Password Validation Function Tests
 *
 * Tests for validate_registration_confirm_password() function
 */

// Load the validation function
require_once __DIR__ .
    "/../../web/wp-content/themes/operation-timeline/inc/user_registration/validate_registration_confirm_password.php";

describe("validate_registration_confirm_password", function () {
    test("returns error when confirm password is empty", function () {
        $result = validate_registration_confirm_password("Test123!", "");
        expect($result)->toBe("Please confirm your password.");
    });

    test("returns error when passwords do not match", function () {
        $result = validate_registration_confirm_password("Test123!", "Different123!");
        expect($result)->toBe("Passwords do not match.");
    });

    test("returns null when passwords match exactly", function () {
        $result = validate_registration_confirm_password("Test123!", "Test123!");
        expect($result)->toBeNull();
    });

    test("returns error for case-sensitive mismatch", function () {
        $result = validate_registration_confirm_password("Test123!", "test123!");
        expect($result)->toBe("Passwords do not match.");
    });

    test("returns null for matching passwords with special characters", function () {
        $password = "C0mpl3x!P@ssw0rd#2024";
        $result = validate_registration_confirm_password($password, $password);
        expect($result)->toBeNull();
    });
});
