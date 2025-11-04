/**
 * User Registration Form - Alpine.js Component
 */

interface RegistrationFormData {
	username: string;
	email: string;
	password: string;
	confirm_password: string;
	first_name: string;
	last_name: string;
	terms: boolean;
}

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
		formData: {
			username: "",
			email: "",
			password: "",
			confirm_password: "",
			first_name: "",
			last_name: "",
			terms: false,
		} as RegistrationFormData,

		errors: {} as ValidationErrors,
		successMessage: "",
		errorMessage: "",
		isSubmitting: false,

		validateField(fieldName: keyof RegistrationFormData): void {
			// Clear previous error for this field
			delete this.errors[fieldName];

			switch (fieldName) {
				case "username":
					if (
						!this.formData.username ||
						this.formData.username.trim().length === 0
					) {
						this.errors.username = "Username is required";
					} else if (this.formData.username.length < 3) {
						this.errors.username = "Username must be at least 3 characters";
					} else if (!/^[a-zA-Z0-9_]+$/.test(this.formData.username)) {
						this.errors.username =
							"Username can only contain letters, numbers, and underscores";
					}
					break;

				case "email":
					if (
						!this.formData.email ||
						this.formData.email.trim().length === 0
					) {
						this.errors.email = "Email is required";
					} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
						this.errors.email = "Please enter a valid email address";
					}
					break;

				case "password":
					if (
						!this.formData.password ||
						this.formData.password.length === 0
					) {
						this.errors.password = "Password is required";
					} else if (this.formData.password.length < 8) {
						this.errors.password = "Password must be at least 8 characters";
					}
					break;

				case "confirm_password":
					if (
						!this.formData.confirm_password ||
						this.formData.confirm_password.length === 0
					) {
						this.errors.confirm_password = "Please confirm your password";
					} else if (
						this.formData.password !== this.formData.confirm_password
					) {
						this.errors.confirm_password = "Passwords do not match";
					}
					break;

				case "terms":
					if (!this.formData.terms) {
						this.errors.terms = "You must agree to the Terms and Conditions";
					}
					break;
			}
		},

		validateAll(): boolean {
			this.errors = {};
			this.validateField("username");
			this.validateField("email");
			this.validateField("password");
			this.validateField("confirm_password");
			this.validateField("terms");
			return Object.keys(this.errors).length === 0;
		},

		async submitForm(event: Event): Promise<void> {
			// Clear previous messages
			this.successMessage = "";
			this.errorMessage = "";

			// Validate all fields
			if (!this.validateAll()) {
				this.errorMessage = "Please fix the errors below before submitting.";
				window.scrollTo({ top: 0, behavior: "smooth" });
				return;
			}

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
					this.formData = {
						username: "",
						email: "",
						password: "",
						confirm_password: "",
						first_name: "",
						last_name: "",
						terms: false,
					};

					// Redirect after 1.5 seconds
					if (result.data.redirect) {
						setTimeout(() => {
							window.location.href = result.data.redirect;
						}, 1500);
					}
				} else {
					this.errorMessage =
						result.data.message || "Registration failed. Please try again.";

					// Display field-specific errors
					if (result.data.errors) {
						this.errors = result.data.errors;
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
