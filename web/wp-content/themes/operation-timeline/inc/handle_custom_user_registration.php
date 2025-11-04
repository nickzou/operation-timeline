<?php

/**
 * User Registration Handler Function
 *
 * Processes AJAX user registration with validation
 */

// Load password validation functions
require_once __DIR__ . '/is_password_too_common.php';

/**
 * Process user registration
 */
function handle_custom_user_registration()
{
    // Verify nonce for security
    if (
        !isset($_POST["registration_nonce"]) ||
        !wp_verify_nonce($_POST["registration_nonce"], "custom_registration_nonce")
    ) {
        wp_send_json_error([
            "message" => "Security verification failed. Please refresh and try again.",
        ]);
        return;
    }

    // Sanitize and validate input
    $username = sanitize_user($_POST["username"] ?? "");
    $email = sanitize_email($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $first_name = sanitize_text_field($_POST["first_name"] ?? "");
    $last_name = sanitize_text_field($_POST["last_name"] ?? "");
    $terms = isset($_POST["terms"]) && $_POST["terms"] === "on";

    // Validation errors array
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors["username"] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters.";
    } elseif (!validate_username($username)) {
        $errors["username"] = "Username contains invalid characters.";
    } elseif (username_exists($username)) {
        $errors["username"] = "This username is already taken.";
    }

    // Validate email
    if (empty($email)) {
        $errors["email"] = "Email is required.";
    } elseif (!is_email($email)) {
        $errors["email"] = "Please enter a valid email address.";
    } elseif (email_exists($email)) {
        $errors["email"] = "This email is already registered.";
    }

    // Validate password
    if (empty($password)) {
        $errors["password"] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters.";
    } elseif (strlen($password) > 64) {
        $errors["password"] = "Password must not exceed 64 characters.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors["password"] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors["password"] = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors["password"] = "Password must contain at least one number.";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors["password"] = "Password must contain at least one special character (!@#$%^&*(),.?\":{}|<>).";
    } elseif (is_password_too_common($password)) {
        $errors["password"] = "This password is too common. Please choose a more secure password.";
    }

    // Validate password confirmation
    if (empty($confirm_password)) {
        $errors["confirm_password"] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // Validate terms acceptance
    if (!$terms) {
        $errors["terms"] = "You must agree to the Terms and Conditions to register.";
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        wp_send_json_error([
            "message" => "Please fix the errors below.",
            "errors" => $errors,
        ]);
        return;
    }

    // Create the user
    $user_data = [
        "user_login" => $username,
        "user_email" => $email,
        "user_pass" => $password,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "role" => "subscriber", // Default role
    ];

    $user_id = wp_insert_user($user_data);

    // Check for errors
    if (is_wp_error($user_id)) {
        wp_send_json_error([
            "message" => "Registration failed: " . $user_id->get_error_message(),
        ]);
        return;
    }

    // Success! Auto-login the user
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    // Send success response
    wp_send_json_success([
        "message" => "Registration successful! Welcome to Operation Timeline.",
        "redirect" => home_url("/"), // Redirect to homepage
    ]);
}
