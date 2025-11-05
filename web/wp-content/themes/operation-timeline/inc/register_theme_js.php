<?php

/**
 * Register all JavaScript files in the theme's js directory
 *
 * @return void
 */
function register_theme_js_files()
{
    $theme_directory = get_template_directory();
    $theme_uri = get_template_directory_uri();

    // Core theme script
    wp_register_script("app", "{$theme_uri}/js/app.js", [], filemtime("{$theme_directory}/js/app.js"));

    // Alpine.js core
    wp_register_script(
        "alpine",
        "https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js",
        ["alpine-intersect"],
        "3.x.x",
        [
            "strategy" => "defer",
            "in_footer" => false,
        ],
    );

    // Alpine.js Intersect plugin
    wp_register_script(
        "alpine-intersect",
        "https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js",
        [],
        "3.x.x",
        [
            "strategy" => "defer",
            "in_footer" => false,
        ],
    );

    // Registration form script
    $registration_form_path = "{$theme_directory}/js/registration-form.js";
    wp_register_script(
        "registration-form",
        "{$theme_uri}/js/registration-form.js",
        [],
        file_exists($registration_form_path) ? filemtime($registration_form_path) : "1.0.0",
        true,
    );
}

/**
 * Hook to enqueue all JS files in theme
 */
add_action("init", "register_theme_js_files");
