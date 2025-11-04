<?php

/**
 * Test Bootstrap
 *
 * This file provides WordPress function stubs for testing purposes.
 * For full WordPress integration tests, you would need to load WordPress core.
 */

// Mock WordPress functions for testing
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        // Stub implementation for testing
        return true;
    }
}

if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data = null, $status_code = null)
    {
        // Stub implementation for testing
        return json_encode(['success' => true, 'data' => $data]);
    }
}

if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data = null, $status_code = null)
    {
        // Stub implementation for testing
        return json_encode(['success' => false, 'data' => $data]);
    }
}

if (!function_exists('check_ajax_referer')) {
    function check_ajax_referer($action = -1, $query_arg = false, $stop = true)
    {
        // Stub implementation for testing
        return true;
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str)
    {
        // Simple sanitization for testing
        return trim(strip_tags($str));
    }
}

if (!function_exists('sanitize_email')) {
    function sanitize_email($email)
    {
        // Simple email sanitization for testing
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

if (!function_exists('username_exists')) {
    function username_exists($username)
    {
        // Stub - returns false for testing
        return false;
    }
}

if (!function_exists('email_exists')) {
    function email_exists($email)
    {
        // Stub - returns false for testing
        return false;
    }
}

if (!function_exists('validate_username')) {
    function validate_username($username)
    {
        // WordPress username validation logic
        // Only allows alphanumerics and underscores
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
    }
}

if (!function_exists('is_email')) {
    function is_email($email)
    {
        // WordPress email validation
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        return false;
    }
}

if (!function_exists('wp_create_user')) {
    function wp_create_user($username, $password, $email = '')
    {
        // Stub - returns mock user ID for testing
        return 1;
    }
}

if (!function_exists('wp_insert_user')) {
    function wp_insert_user($userdata)
    {
        // Stub - returns mock user ID for testing
        return 1;
    }
}

if (!function_exists('wp_delete_user')) {
    function wp_delete_user($id, $reassign = null)
    {
        // Stub - returns true for testing
        return true;
    }
}

if (!function_exists('get_user_by')) {
    function get_user_by($field, $value)
    {
        // Stub - returns false for testing
        return false;
    }
}

if (!function_exists('home_url')) {
    function home_url($path = '')
    {
        // Stub - returns a test URL
        return 'http://example.com' . $path;
    }
}
