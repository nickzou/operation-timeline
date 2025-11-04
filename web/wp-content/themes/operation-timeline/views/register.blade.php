@extends("base.base")

@section("content")
    <main class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-md" x-data="passwordStrength()">
            <div class="text-center">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Create Your Account</h1>
                <p class="mb-8 text-gray-600">Join the Operation Timeline community</p>
            </div>

            <div class="rounded-lg bg-white px-8 py-10 shadow-md">
                <!-- Success Message -->
                @if(!empty($success_message))
                    <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                        <p class="text-sm">{{ $success_message }}</p>
                    </div>
                @endif

                <!-- General Error Message -->
                @if(isset($errors['general']))
                    <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                        <p class="text-sm">{{ $errors['general'] }}</p>
                    </div>
                @endif

                <form method="POST" action="" novalidate class="space-y-6">
                    <?php wp_nonce_field("custom_registration_nonce", "registration_nonce"); ?>

                    <!-- Username -->
                    <div>
                        <label for="username" class="mb-2 block text-sm font-medium text-gray-700">Username *</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ $form_data['username'] ?? '' }}"
                            required
                            class="w-full rounded-md border {{ isset($errors['username']) ? 'border-red-500' : 'border-gray-300' }} px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Choose a username"
                        />
                        @if(isset($errors['username']))
                            <p class="mt-1 text-xs text-red-600">{{ $errors['username'] }}</p>
                        @endif
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ $form_data['email'] ?? '' }}"
                            required
                            class="w-full rounded-md border {{ isset($errors['email']) ? 'border-red-500' : 'border-gray-300' }} px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="your@email.com"
                        />
                        @if(isset($errors['email']))
                            <p class="mt-1 text-xs text-red-600">{{ $errors['email'] }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password *</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            x-model="password"
                            required
                            minlength="8"
                            maxlength="64"
                            class="w-full rounded-md border {{ isset($errors['password']) ? 'border-red-500' : 'border-gray-300' }} px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Create a strong password"
                        />

                        <!-- Password Strength Indicator (Alpine.js enhancement) -->
                        <div x-show="password" class="mt-2">
                            <div class="mb-1 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                                <span
                                    class="text-xs font-semibold"
                                    :class="{
                                        'text-red-600': strength.color === 'red',
                                        'text-orange-600': strength.color === 'orange',
                                        'text-yellow-600': strength.color === 'yellow',
                                        'text-green-600': strength.color === 'green'
                                    }"
                                    x-text="strength.label"
                                ></span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                <div
                                    class="h-full transition-all duration-300"
                                    :class="{
                                        'bg-red-500': strength.color === 'red',
                                        'bg-orange-500': strength.color === 'orange',
                                        'bg-yellow-500': strength.color === 'yellow',
                                        'bg-green-500': strength.color === 'green'
                                    }"
                                    :style="'width: ' + (strength.score / 7 * 100) + '%'"
                                ></div>
                            </div>
                        </div>

                        <!-- Password Requirements (Alpine.js enhancement) -->
                        <div x-show="password" class="mt-2 text-xs text-gray-600">
                            <p class="mb-1 font-medium">Password must contain:</p>
                            <ul class="ml-4 space-y-0.5">
                                <li :class="password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                                    <span x-text="password.length >= 8 ? '✓' : '○'"></span>
                                    At least 8 characters
                                </li>
                                <li :class="/[A-Z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                                    <span x-text="/[A-Z]/.test(password) ? '✓' : '○'"></span>
                                    One uppercase letter
                                </li>
                                <li :class="/[a-z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                                    <span x-text="/[a-z]/.test(password) ? '✓' : '○'"></span>
                                    One lowercase letter
                                </li>
                                <li :class="/[0-9]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                                    <span x-text="/[0-9]/.test(password) ? '✓' : '○'"></span>
                                    One number
                                </li>
                                <li :class="/[!@#$%^&*(),.?\\:{}|<>]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                                    <span x-text="/[!@#$%^&*(),.?\\:{}|<>]/.test(password) ? '✓' : '○'"></span>
                                    One special character
                                </li>
                            </ul>
                        </div>

                        @if(isset($errors['password']))
                            <p class="mt-1 text-xs text-red-600">{{ $errors['password'] }}</p>
                        @endif
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
                            required
                            minlength="8"
                            class="w-full rounded-md border {{ isset($errors['confirm_password']) ? 'border-red-500' : 'border-gray-300' }} px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Confirm your password"
                        />
                        @if(isset($errors['confirm_password']))
                            <p class="mt-1 text-xs text-red-600">{{ $errors['confirm_password'] }}</p>
                        @endif
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ $form_data['first_name'] ?? '' }}"
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
                            value="{{ $form_data['last_name'] ?? '' }}"
                            class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Your last name"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full rounded-md bg-blue-600 px-4 py-3 font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Create Account
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
@endsection
