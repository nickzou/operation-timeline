<?php

/**
 * Common Password Detection Tests
 *
 * Tests for the is_password_too_common() function that detects common passwords.
 *
 * Note: WordPress functions are available via mocks defined in tests/bootstrap.php.
 * The is_password_too_common() function is loaded from inc/user_registration/is_password_too_common.php.
 */

// Load the function
require_once __DIR__ .
    "/../../web/wp-content/themes/operation-timeline/inc/user_registration/is_password_too_common.php";

describe("Common Password Detection", function () {
    test('detects common password "password"', function () {
        expect(is_password_too_common("password"))->toBeTrue();
    });

    test('detects common password "password123"', function () {
        expect(is_password_too_common("password123"))->toBeTrue();
    });

    test('detects common password "Password123!"', function () {
        expect(is_password_too_common("Password123!"))->toBeTrue();
    });

    test('detects common password "12345678"', function () {
        expect(is_password_too_common("12345678"))->toBeTrue();
    });

    test('detects common password "qwerty"', function () {
        expect(is_password_too_common("qwerty"))->toBeTrue();
    });

    test("detects all common passwords in list", function () {
        $commonPasswords = [
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

        foreach ($commonPasswords as $password) {
            expect(is_password_too_common($password))->toBeTrue("Password '{$password}' should be detected as common");
        }
    });
});
