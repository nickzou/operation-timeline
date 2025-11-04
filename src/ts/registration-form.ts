/**
 * User Registration Form - Alpine.js Component with Simple Validate Plugin
 */

interface ValidationErrors {
	[key: string]: string;
}

interface AjaxResponse {
	success: boolean;
	data: {
		message: string;
		errors?: ValidationErrors;
		redirect?: string;
	};
}

document.addEventListener("alpine:init", () => {
	Alpine.data("registrationForm", () => ({
		// Form data - bound directly to inputs with x-model
		username: "",
		email: "",
		password: "",
		confirm_password: "",
		first_name: "",
		last_name: "",
		terms: false,

		// Validation rules for simple-validate plugin
		rules: {
			username: {
				required: true,
				minlength: 3,
				pattern: /^[a-zA-Z0-9_]+$/,
			},
			email: {
				required: true,
				email: true,
			},
			password: {
				required: true,
				minlength: 8,
			},
			confirm_password: {
				required: true,
				match: "password",
			},
			terms: {
				required: true,
			},
		},

		// Custom validation messages
		messages: {
			username: {
				required: "Username is required",
				minlength: "Username must be at least 3 characters",
				pattern:
					"Username can only contain letters, numbers, and underscores",
			},
			email: {
				required: "Email is required",
				email: "Please enter a valid email address",
			},
			password: {
				required: "Password is required",
				minlength: "Password must be at least 8 characters",
			},
			confirm_password: {
				required: "Please confirm your password",
				match: "Passwords do not match",
			},
			terms: {
				required: "You must agree to the Terms and Conditions",
			},
		},

		// Backend validation errors (from server response)
		serverErrors: {} as ValidationErrors,

		// UI state
		successMessage: "",
		errorMessage: "",
		isSubmitting: false,

		async submitForm(event: Event): Promise<void> {
			// Clear previous messages
			this.successMessage = "";
			this.errorMessage = "";
			this.serverErrors = {};

			// Note: simple-validate plugin handles frontend validation automatically
			// If form is invalid, the plugin will prevent submission

			this.isSubmitting = true;

			try {
				const target = event.target as HTMLFormElement;
				const formData = new FormData(target);

				const response = await fetch(target.action, {
					method: "POST",
					body: formData,
				});

				const result: AjaxResponse = await response.json();

				if (result.success) {
					this.successMessage = result.data.message;
					this.errorMessage = "";

					// Reset form
					this.username = "";
					this.email = "";
					this.password = "";
					this.confirm_password = "";
					this.first_name = "";
					this.last_name = "";
					this.terms = false;

					// Redirect after 1.5 seconds
					if (result.data.redirect) {
						setTimeout(() => {
							window.location.href = result.data.redirect;
						}, 1500);
					}
				} else {
					this.errorMessage =
						result.data.message || "Registration failed. Please try again.";

					// Display backend validation errors
					if (result.data.errors) {
						this.serverErrors = result.data.errors;
					}
				}
			} catch (error) {
				this.errorMessage = "An error occurred. Please try again later.";
				console.error("Registration error:", error);
			} finally {
				this.isSubmitting = false;
				window.scrollTo({ top: 0, behavior: "smooth" });
			}
		},
	}));
});
