@extends("base.base")

@section("content")
    <main class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-md" x-data="registrationForm()">
            <div class="text-center">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Create Your Account</h1>
                <p class="mb-8 text-gray-600">Join the Operation Timeline community</p>
            </div>

            <div class="rounded-lg bg-white px-8 py-10 shadow-md">
                <!-- Success Message -->
                <div x-show="successMessage" x-transition class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    <p class="text-sm" x-text="successMessage"></p>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" x-transition class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <p class="text-sm" x-text="errorMessage"></p>
                </div>

                <form
                    @submit.prevent="submitForm"
                    method="POST"
                    action="{{ home_url("/wp-admin/admin-ajax.php") }}"
                    class="space-y-6"
                >
                    <input type="hidden" name="action" value="custom_user_registration" />

                    <?php wp_nonce_field("custom_registration_nonce", "registration_nonce"); ?>

                    <!-- Username -->
                    <div>
                        <label for="username" class="mb-2 block text-sm font-medium text-gray-700">Username *</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            x-model="formData.username"
                            @blur="validateField('username')"
                            required
                            :class="errors.username ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                            class="w-full rounded-md border px-4 py-2 focus:border-blue-500 focus:ring-2"
                            placeholder="Choose a username"
                        />
                        <p x-show="errors.username" x-text="errors.username" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            x-model="formData.email"
                            @blur="validateField('email')"
                            required
                            :class="errors.email ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                            class="w-full rounded-md border px-4 py-2 focus:border-blue-500 focus:ring-2"
                            placeholder="your@email.com"
                        />
                        <p x-show="errors.email" x-text="errors.email" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password *</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            x-model="formData.password"
                            @blur="validateField('password')"
                            required
                            minlength="8"
                            :class="errors.password ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                            class="w-full rounded-md border px-4 py-2 focus:border-blue-500 focus:ring-2"
                            placeholder="At least 8 characters"
                        />
                        <p x-show="!errors.password" class="mt-1 text-xs text-gray-500">Must be at least 8 characters long</p>
                        <p x-show="errors.password" x-text="errors.password" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirm_password" class="mb-2 block text-sm font-medium text-gray-700">
                            Confirm Password *
                        </label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            x-model="formData.confirm_password"
                            @blur="validateField('confirm_password')"
                            required
                            minlength="8"
                            :class="errors.confirm_password ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                            class="w-full rounded-md border px-4 py-2 focus:border-blue-500 focus:ring-2"
                            placeholder="Confirm your password"
                        />
                        <p x-show="errors.confirm_password" x-text="errors.confirm_password" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            x-model="formData.first_name"
                            class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Your first name"
                        />
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            x-model="formData.last_name"
                            class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Your last name"
                        />
                    </div>

                    <!-- Terms and Conditions -->
                    <div>
                        <div class="flex items-start">
                            <input
                                type="checkbox"
                                id="terms"
                                name="terms"
                                x-model="formData.terms"
                                @change="validateField('terms')"
                                required
                                :class="errors.terms ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="mt-1 h-4 w-4 rounded text-blue-600"
                            />
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                I agree to the
                                <a href="#" class="text-blue-600 underline hover:text-blue-800">Terms and Conditions</a>
                                *
                            </label>
                        </div>
                        <p x-show="errors.terms" x-text="errors.terms" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="w-full rounded-md bg-blue-600 px-4 py-3 font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                            x-text="isSubmitting ? 'Creating Account...' : 'Create Account'"
                        >
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ wp_login_url() }}" class="font-medium text-blue-600 underline hover:text-blue-800">
                            Log in
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registrationForm', () => ({
                formData: {
                    username: '',
                    email: '',
                    password: '',
                    confirm_password: '',
                    first_name: '',
                    last_name: '',
                    terms: false
                },
                errors: {},
                successMessage: '',
                errorMessage: '',
                isSubmitting: false,

                validateField(fieldName) {
                    // Clear previous error for this field
                    delete this.errors[fieldName];

                    switch (fieldName) {
                        case 'username':
                            if (!this.formData.username || this.formData.username.trim().length === 0) {
                                this.errors.username = 'Username is required';
                            } else if (this.formData.username.length < 3) {
                                this.errors.username = 'Username must be at least 3 characters';
                            } else if (!/^[a-zA-Z0-9_]+$/.test(this.formData.username)) {
                                this.errors.username = 'Username can only contain letters, numbers, and underscores';
                            }
                            break;

                        case 'email':
                            if (!this.formData.email || this.formData.email.trim().length === 0) {
                                this.errors.email = 'Email is required';
                            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
                                this.errors.email = 'Please enter a valid email address';
                            }
                            break;

                        case 'password':
                            if (!this.formData.password || this.formData.password.length === 0) {
                                this.errors.password = 'Password is required';
                            } else if (this.formData.password.length < 8) {
                                this.errors.password = 'Password must be at least 8 characters';
                            }
                            break;

                        case 'confirm_password':
                            if (!this.formData.confirm_password || this.formData.confirm_password.length === 0) {
                                this.errors.confirm_password = 'Please confirm your password';
                            } else if (this.formData.password !== this.formData.confirm_password) {
                                this.errors.confirm_password = 'Passwords do not match';
                            }
                            break;

                        case 'terms':
                            if (!this.formData.terms) {
                                this.errors.terms = 'You must agree to the Terms and Conditions';
                            }
                            break;
                    }
                },

                validateAll() {
                    this.errors = {};
                    this.validateField('username');
                    this.validateField('email');
                    this.validateField('password');
                    this.validateField('confirm_password');
                    this.validateField('terms');
                    return Object.keys(this.errors).length === 0;
                },

                async submitForm(event) {
                    // Clear previous messages
                    this.successMessage = '';
                    this.errorMessage = '';

                    // Validate all fields
                    if (!this.validateAll()) {
                        this.errorMessage = 'Please fix the errors below before submitting.';
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const formData = new FormData(event.target);

                        const response = await fetch(event.target.action, {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.successMessage = result.data.message;
                            this.errorMessage = '';

                            // Reset form
                            this.formData = {
                                username: '',
                                email: '',
                                password: '',
                                confirm_password: '',
                                first_name: '',
                                last_name: '',
                                terms: false
                            };

                            // Redirect after 1.5 seconds
                            if (result.data.redirect) {
                                setTimeout(() => {
                                    window.location.href = result.data.redirect;
                                }, 1500);
                            }
                        } else {
                            this.errorMessage = result.data.message || 'Registration failed. Please try again.';

                            // Display field-specific errors
                            if (result.data.errors) {
                                this.errors = result.data.errors;
                            }
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again later.';
                        console.error('Registration error:', error);
                    } finally {
                        this.isSubmitting = false;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }))
        });
    </script>
@endsection
