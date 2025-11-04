# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Modern WordPress boilerplate with infrastructure as code for the "Operation Timeline" project - a WWII film timeline cataloguing system. Uses Terraform + DigitalOcean for infrastructure, BladeOne for templating, and modern frontend tooling.

**Tech Stack:**
- Backend: WordPress, PHP 8.4, MySQL 8.4, Nginx, Redis
- Frontend: Tailwind CSS 4, TypeScript, Alpine.js, BladeOne templates
- Infrastructure: Terraform, DigitalOcean, Cloudflare, Ubuntu 24.04
- DevOps: GitHub Actions, Docker (wp-env), Composer, Netdata
- Testing: Pest PHP

**Local Environment:** Docker-based via `@wordpress/env` (wp-env) running on port 8888

## Common Commands

### Development Workflow
```bash
# Start local WordPress environment
npm run env:start              # First time or after restart
npm run env:stop               # Stop local environment

# Development with hot reload (primary command for active development)
npm run watch                  # Runs all watchers + BrowserSync

# Build commands
npm run dev                    # Development build (unminified)
npm run prod                   # Production build (minified)

# Individual watchers (if you need just one)
npm run tailwind:watch         # Tailwind CSS compilation
npm run ts:watch               # TypeScript compilation
npm run php:format:watch       # PHP auto-formatting
npm run wp:blocks:watch        # Gutenberg blocks
```

### Testing
```bash
# PHP unit tests (Pest)
./vendor/bin/pest              # Run all tests
./vendor/bin/pest --filter=CommonPassword  # Run specific test

# TypeScript type checking
npm run ts:typecheck           # One-time check
npm run ts:typecheck:watch     # Watch mode
```

### Code Quality
```bash
npm run php:format             # Format PHP with Prettier
npm run php:lint               # Lint PHP files
npm run biome                  # Check/format TypeScript with Biome
```

### Infrastructure & Deployment
```bash
npm run setup:infra            # Deploy infrastructure (one-time)
npm run ssh                    # SSH into production server
npm run sync:local             # Pull production data to local
npm run sync:staging           # Sync prod → staging
npm run sync:dev               # Sync prod → dev

# Gutenberg block creation
npm run wp:create-block        # Interactive block scaffolding
```

## Architecture

### Theme Structure - One File Per Function

**Critical Pattern:** All theme functions follow a "one file per function" convention in the `inc/` directory.

```
web/wp-content/themes/operation-timeline/
├── functions.php                    # Auto-loads ALL files from inc/ recursively
├── inc/                             # Theme functionality (one file per function)
│   ├── is_password_too_common.php   # Single function: is_password_too_common()
│   ├── handle_custom_user_registration.php  # Single function: handle_custom_user_registration()
│   ├── user_registration.php        # Orchestrator: requires functions + registers AJAX hooks
│   ├── register_theme_js.php        # Registers JS assets globally
│   ├── enqueue_registration_assets.php  # Conditionally enqueues assets
│   └── ...other single-function files
├── views/                           # BladeOne templates
│   ├── base/                        # Base layouts
│   ├── components/                  # Reusable components
│   ├── globals/                     # Global partials (header, footer)
│   └── *.blade.php                  # Page templates
├── page-*.php                       # WordPress page templates
└── blocks/                          # Compiled Gutenberg blocks
```

**Key Points:**
- `functions.php` uses `RecursiveDirectoryIterator` to auto-load all PHP files in `inc/` and subdirectories
- Each file in `inc/` should contain exactly one function (unless explicitly specified otherwise)
- Orchestrator files (like `user_registration.php`) are exceptions - they require functions and register hooks
- No need to manually include files in `functions.php` - just drop them in `inc/`

### Frontend Build Pipeline

```
src/
├── ts/                              # TypeScript source (compiled by Parcel)
│   └── *.ts                         # Individual entry points
├── css/                             # Custom CSS (compiled by LightningCSS)
├── blocks/                          # Gutenberg block source (@wordpress/scripts)
└── components/                      # React components for blocks

↓ Compilation ↓

web/wp-content/themes/operation-timeline/
├── js/                              # Compiled TypeScript
├── css/                             # Compiled CSS + Tailwind
└── blocks/                          # Compiled Gutenberg blocks
```

**Build Tools:**
- **TypeScript:** Parcel (dev/prod/watch modes)
- **CSS:** Tailwind CLI v4 + LightningCSS
- **Blocks:** @wordpress/scripts
- **PHP:** Prettier for formatting, custom linter
- **Quality:** Biome for TypeScript/JS

### BladeOne Templating System

WordPress templates use BladeOne instead of raw PHP:

```php
// page-register.php (WordPress template)
<?php
require_once get_template_directory() . '/inc/use_blade.php';
echo render_blade('register');  // Renders views/register.blade.php
```

**Blade Features Used:**
- `@extends('base.base')` - Layout inheritance
- `@section('content')` - Content sections
- `{{ $variable }}` - Variable output (escaped)
- `{!! $html !!}` - Raw HTML output
- Alpine.js integration via `x-data`, `x-model`, etc.

### Alpine.js Component Pattern

Frontend reactive components use Alpine.js with TypeScript:

```typescript
// src/ts/registration-form.ts
document.addEventListener("alpine:init", () => {
    Alpine.data("registrationForm", () => ({
        formData: { username: "", email: "", ... },
        errors: {},
        validateField(fieldName) { ... },
        async submitForm() { ... }
    }));
});
```

```blade
<!-- views/register.blade.php -->
<form x-data="registrationForm()" @submit.prevent="submitForm">
    <input x-model="formData.username" @blur="validateField('username')" />
</form>
```

### AJAX Handler Pattern

WordPress AJAX handlers follow this pattern:

```php
// inc/handle_custom_user_registration.php
function handle_custom_user_registration() {
    // Verify nonce
    if (!wp_verify_nonce($_POST["nonce"], "action_name")) {
        wp_send_json_error(["message" => "Security check failed"]);
        return;
    }

    // Process and respond
    wp_send_json_success(["message" => "Success", "data" => $data]);
}

// inc/user_registration.php (orchestrator)
require_once __DIR__ . '/handle_custom_user_registration.php';
if (function_exists("add_action")) {
    add_action("wp_ajax_nopriv_action_name", "handle_custom_user_registration");
    add_action("wp_ajax_action_name", "handle_custom_user_registration");
}
```

### Script/Style Registration Pattern

**Two-step process:**
1. **Register globally** in `inc/register_theme_js.php` or `inc/register_theme_css.php`
2. **Enqueue conditionally** in dedicated `inc/enqueue_*_assets.php` files

```php
// inc/register_theme_js.php
function register_theme_js() {
    wp_register_script("registration-form",
        get_template_uri() . "/js/registration-form.js",
        [], filemtime($path), true);
}

// inc/enqueue_registration_assets.php
function enqueue_registration_form_assets() {
    if (is_page_template("page-register.php")) {
        wp_enqueue_script("registration-form");
    }
}
```

### Testing Strategy

**PHP Tests (Pest):**
- Only test functions you wrote (not WordPress core functions)
- Use mocks in `tests/bootstrap.php` for WordPress functions
- Test files: `tests/Unit/*.php`
- Configuration: `tests/Pest.php` (auto-loads bootstrap)

**Example:**
```php
// tests/Unit/PasswordValidationTest.php
require_once __DIR__ . '/../../web/wp-content/themes/operation-timeline/inc/is_password_too_common.php';

test('detects common password "password"', function () {
    expect(is_password_too_common('password'))->toBeTrue();
});
```

**What NOT to test in unit tests:**
- WordPress core functions (`validate_username()`, `is_email()`, etc.)
- AJAX handlers that depend heavily on $_POST, database, and exit()
- Integration flows (better tested with E2E or manual testing)

## Important Conventions

### Naming Conventions
- **PHP files/functions:** snake_case (`is_password_too_common.php`)
- **Directories:** PascalCase (`tests/Unit/`) - NO SPACES
- **Git branches:** kebab-case (`feature/custom-user-registration`)
- **CSS classes:** Tailwind utilities (functional CSS)
- **TypeScript:** camelCase for variables, PascalCase for types/interfaces

### Git Workflow
- **Main branch:** `main` (not master)
- **Feature branches:** Create from `main`, PR back to `main`
- **Commit messages:** Clear, concise, imperative mood
- **Auto-commit footer:** Include Claude Code attribution if using Claude Code

### Environment Variables
- Store in `.env` (never commit this file)
- Values with spaces must be quoted: `TF_PROJECT_NAME="Operation Timeline"`
- Theme slug: `operation-timeline` (used throughout codebase)

### Infrastructure Setup
- Running `npm run setup:infra` automatically sets GitHub secret `DROPLET_IP`
- This secret is used by GitHub Actions to determine if infrastructure exists
- Preview environments auto-deploy for feature branches (except `main`)

## Key Files to Reference

- **Theme entry point:** `web/wp-content/themes/operation-timeline/functions.php`
- **BladeOne setup:** `web/wp-content/themes/operation-timeline/inc/use_blade.php`
- **Test bootstrap:** `tests/bootstrap.php` (WordPress function mocks)
- **Tailwind config:** `tailwind.css` (imports, layers, utilities)
- **TypeScript config:** `tsconfig.json`
- **Local WP setup:** `.wp-env.json` (wp-env configuration)
- **Build orchestration:** `package.json` scripts

## GitHub Actions

- **Deploy:** Deploys to production/staging/dev on push to respective branches
- **Preview:** Auto-creates preview environment for feature branches
- **Cleanup:** Removes preview environments when branches are deleted/merged (only if `DROPLET_IP` secret exists)

## Costs

Production environment runs ~$12-13/month (DigitalOcean 2GB droplet + domain)
