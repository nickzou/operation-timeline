<?php
/**
 * Template Name: User Registration
 * Description: Custom user registration page
 */

// Initialize variables for the view
$errors = [];
$success_message = "";
$form_data = [
    "username" => "",
    "email" => "",
    "first_name" => "",
    "last_name" => "",
];

// Handle POST request (form submission)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registration_nonce"])) {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST["registration_nonce"], "custom_registration_nonce")) {
        $errors["general"] = "Security verification failed. Please try again.";
    } else {
        // Sanitize input
        $username = sanitize_user($_POST["username"] ?? "");
        $email = sanitize_email($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";
        $first_name = sanitize_text_field($_POST["first_name"] ?? "");
        $last_name = sanitize_text_field($_POST["last_name"] ?? "");

        // Store form data to repopulate fields on error
        $form_data = [
            "username" => $username,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
        ];

        // Validate each field
        $username_error = validate_registration_username($username);
        if ($username_error !== null) {
            $errors["username"] = $username_error;
        }

        $email_error = validate_registration_email($email);
        if ($email_error !== null) {
            $errors["email"] = $email_error;
        }

        $password_error = validate_registration_password($password);
        if ($password_error !== null) {
            $errors["password"] = $password_error;
        }

        $confirm_password_error = validate_registration_confirm_password($password, $confirm_password);
        if ($confirm_password_error !== null) {
            $errors["confirm_password"] = $confirm_password_error;
        }

        // If no errors, create the user
        if (empty($errors)) {
            $user_id = wp_insert_user([
                "user_login" => $username,
                "user_email" => $email,
                "user_pass" => $password,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "role" => "subscriber",
            ]);

            if (is_wp_error($user_id)) {
                $errors["general"] = $user_id->get_error_message();
            } else {
                // Log the user in
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);

                // Redirect to homepage
                wp_redirect(home_url("/"));
                exit();
            }
        }
    }
}

// Render the view with errors and form data
view("register", [
    "errors" => $errors,
    "success_message" => $success_message,
    "form_data" => $form_data,
]);
?>
