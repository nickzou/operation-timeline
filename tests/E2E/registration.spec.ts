import { test, expect } from '@playwright/test';

/**
 * User Registration E2E Tests
 *
 * Tests the complete user registration flow including:
 * - Form validation
 * - Password strength indicator
 * - Common password detection
 * - Successful registration
 */

test.describe('User Registration', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to registration page before each test
    await page.goto('/register');
  });

  test('displays registration form with all fields', async ({ page }) => {
    // Check all form fields are present
    await expect(page.locator('#username')).toBeVisible();
    await expect(page.locator('#email')).toBeVisible();
    await expect(page.locator('#password')).toBeVisible();
    await expect(page.locator('#confirm_password')).toBeVisible();
    await expect(page.locator('#first_name')).toBeVisible();
    await expect(page.locator('#last_name')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('shows validation errors for empty required fields', async ({ page }) => {
    // Click submit without filling anything
    await page.click('button[type="submit"]');

    // Wait for validation errors to appear
    await expect(page.locator('text=Username is required')).toBeVisible();
    await expect(page.locator('text=Email is required')).toBeVisible();
    await expect(page.locator('text=Password is required')).toBeVisible();
  });

  test('validates username minimum length', async ({ page }) => {
    await page.fill('#username', 'ab');
    await page.blur('#username');

    await expect(page.locator('text=Username must be at least 3 characters')).toBeVisible();
  });

  test('validates username invalid characters', async ({ page }) => {
    await page.fill('#username', 'user name');
    await page.blur('#username');

    await expect(page.locator('text=Username can only contain letters, numbers, and underscores')).toBeVisible();
  });

  test('validates email format', async ({ page }) => {
    await page.fill('#email', 'notanemail');
    await page.blur('#email');

    await expect(page.locator('text=Please enter a valid email address')).toBeVisible();
  });

  test('validates password minimum length', async ({ page }) => {
    await page.fill('#password', 'Test1!');
    await page.blur('#password');

    await expect(page.locator('text=Password must be at least 8 characters')).toBeVisible();
  });

  test('validates password requires uppercase letter', async ({ page }) => {
    await page.fill('#password', 'test1234!');

    // Wait for error to appear
    await expect(page.locator('text=Password must contain at least one uppercase letter')).toBeVisible();
  });

  test('validates password requires lowercase letter', async ({ page }) => {
    await page.fill('#password', 'TEST1234!');

    await expect(page.locator('text=Password must contain at least one lowercase letter')).toBeVisible();
  });

  test('validates password requires number', async ({ page }) => {
    await page.fill('#password', 'TestPass!');

    await expect(page.locator('text=Password must contain at least one number')).toBeVisible();
  });

  test('validates password requires special character', async ({ page }) => {
    await page.fill('#password', 'TestPass1');

    await expect(page.locator('text=Password must contain at least one special character')).toBeVisible();
  });

  test('detects common password', async ({ page }) => {
    await page.fill('#password', 'password123');

    await expect(page.locator('text=This password is too common')).toBeVisible();
  });

  test('shows password strength indicator', async ({ page }) => {
    await page.fill('#password', 'Test1234!');

    // Password strength indicator should be visible
    await expect(page.locator('text=Password Strength:')).toBeVisible();

    // Should show some strength level
    const strengthText = page.locator('span:has-text("Weak"), span:has-text("Fair"), span:has-text("Good"), span:has-text("Strong")');
    await expect(strengthText.first()).toBeVisible();
  });

  test('shows password requirements checklist', async ({ page }) => {
    await page.fill('#password', 'Test');

    // Requirements list should be visible
    await expect(page.locator('text=Password must contain:')).toBeVisible();
    await expect(page.locator('text=At least 8 characters')).toBeVisible();
    await expect(page.locator('text=One uppercase letter')).toBeVisible();
    await expect(page.locator('text=One lowercase letter')).toBeVisible();
    await expect(page.locator('text=One number')).toBeVisible();
    await expect(page.locator('text=One special character')).toBeVisible();
  });

  test('validates passwords match', async ({ page }) => {
    await page.fill('#password', 'Test1234!');
    await page.fill('#confirm_password', 'Different1234!');
    await page.blur('#confirm_password');

    await expect(page.locator('text=Passwords do not match')).toBeVisible();
  });

  test('successful registration with valid data', async ({ page }) => {
    // Generate unique username and email for this test
    const timestamp = Date.now();
    const username = `testuser${timestamp}`;
    const email = `testuser${timestamp}@example.com`;

    // Fill in the form with valid data
    await page.fill('#username', username);
    await page.fill('#email', email);
    await page.fill('#password', 'SecureP@ss123');
    await page.fill('#confirm_password', 'SecureP@ss123');
    await page.fill('#first_name', 'Test');
    await page.fill('#last_name', 'User');

    // Submit the form
    await page.click('button[type="submit"]');

    // Should show success message
    await expect(page.locator('text=Registration successful')).toBeVisible({ timeout: 10000 });

    // Should redirect to homepage after success
    // Wait a bit for the redirect (1.5s timeout in the code)
    await page.waitForURL('/', { timeout: 5000 });
    expect(page.url()).toBe('http://localhost:8888/');
  });

  test('shows error for duplicate username', async ({ page }) => {
    // This test assumes 'admin' user exists in WordPress by default
    await page.fill('#username', 'admin');
    await page.fill('#email', 'newuser@example.com');
    await page.fill('#password', 'SecureP@ss123');
    await page.fill('#confirm_password', 'SecureP@ss123');

    await page.click('button[type="submit"]');

    await expect(page.locator('text=This username is already taken')).toBeVisible({ timeout: 10000 });
  });
});
