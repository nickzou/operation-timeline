<?php

/**
 * Email Validation Function Tests
 *
 * Tests for validate_registration_email() function
 *
 * Note: Tests that check email_exists() are skipped as they require WordPress database.
 * We only test the validation logic that doesn't depend on database state.
 */

// Load the validation function
require_once __DIR__ .
    "/../../web/wp-content/themes/operation-timeline/inc/user_registration/validate_registration_email.php";

describe("validate_registration_email", function () {
    test("returns error for empty email", function () {
        $result = validate_registration_email("");
        expect($result)->toBe("Email is required.");
    });

    test("returns error for email without @ symbol", function () {
        $result = validate_registration_email("testuser.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email without domain", function () {
        $result = validate_registration_email("test@");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email without local part", function () {
        $result = validate_registration_email("@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email without TLD", function () {
        $result = validate_registration_email("test@example");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email with multiple @ symbols", function () {
        $result = validate_registration_email("test@@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email with spaces", function () {
        $result = validate_registration_email("test user@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email with consecutive dots", function () {
        $result = validate_registration_email("user..name@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email starting with dot", function () {
        $result = validate_registration_email(".user@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    test("returns error for email ending with dot in local part", function () {
        $result = validate_registration_email("user.@example.com");
        expect($result)->toBe("Please enter a valid email address.");
    });

    // Note: We can't test if email passes all validation without mocking email_exists()
    // These tests verify format validation only - the function may still return "already registered" error
});
