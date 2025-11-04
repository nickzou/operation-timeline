<?php

/**
 * Password Validation Function Tests
 *
 * Tests for validate_registration_password() function
 *
 * Note: WordPress functions and is_password_too_common() are available via
 * files loaded from inc/user_registration/ by tests/bootstrap.php or auto-loading
 */

// Load the validation function
require_once __DIR__ .
    "/../../web/wp-content/themes/operation-timeline/inc/user_registration/validate_registration_password.php";

describe("validate_registration_password", function () {
    test("returns error for empty password", function () {
        $result = validate_registration_password("");
        expect($result)->toBe("Password is required.");
    });

    test("returns error for password less than 8 characters", function () {
        $result = validate_registration_password("Test1!");
        expect($result)->toBe("Password must be at least 8 characters.");
    });

    test("returns error for password more than 64 characters", function () {
        $password = str_repeat("A", 65) . "1!a";
        $result = validate_registration_password($password);
        expect($result)->toBe("Password must not exceed 64 characters.");
    });

    test("returns error for password without uppercase letter", function () {
        $result = validate_registration_password("test1234!");
        expect($result)->toBe("Password must contain at least one uppercase letter.");
    });

    test("returns error for password without lowercase letter", function () {
        $result = validate_registration_password("TEST1234!");
        expect($result)->toBe("Password must contain at least one lowercase letter.");
    });

    test("returns error for password without number", function () {
        $result = validate_registration_password("TestPass!");
        expect($result)->toBe("Password must contain at least one number.");
    });

    test("returns error for password without special character", function () {
        $result = validate_registration_password("TestPass1");
        expect($result)->toContain("special character");
    });

    test("returns error for common password", function () {
        $result = validate_registration_password("Password123!");
        expect($result)->toBe("This password is too common. Please choose a more secure password.");
    });

    test("returns null for valid password", function () {
        $result = validate_registration_password("SecureP@ss123");
        expect($result)->toBeNull();
    });

    test("accepts password with exactly 8 characters", function () {
        $result = validate_registration_password("Test123!");
        expect($result)->toBeNull();
    });

    test("accepts password with exactly 64 characters", function () {
        $password = str_repeat("A", 60) . "1!aB";
        expect(strlen($password))->toBe(64);
        $result = validate_registration_password($password);
        expect($result)->toBeNull();
    });
});
