/**
 * User Registration Form - Alpine.js Component
 * Simplified version for password strength indicator only
 * Form submission and validation handled server-side
 */

document.addEventListener("alpine:init", () => {
	Alpine.data("passwordStrength", () => ({
		password: "",

		get strength(): { score: number; label: string; color: string } {
			if (!this.password) {
				return { score: 0, label: "", color: "" };
			}

			let score = 0;

			// Length scoring
			if (this.password.length >= 8) score += 1;
			if (this.password.length >= 12) score += 1;
			if (this.password.length >= 16) score += 1;

			// Complexity scoring
			if (/[a-z]/.test(this.password)) score += 1;
			if (/[A-Z]/.test(this.password)) score += 1;
			if (/[0-9]/.test(this.password)) score += 1;
			if (/[!@#$%^&*(),.?":{}|<>]/.test(this.password)) score += 1;

			// Penalty for common passwords
			if (this.isPasswordTooCommon(this.password)) {
				score = Math.max(0, score - 3);
			}

			// Determine strength label and color
			if (score <= 2) {
				return { score, label: "Weak", color: "red" };
			} else if (score <= 4) {
				return { score, label: "Fair", color: "orange" };
			} else if (score <= 6) {
				return { score, label: "Good", color: "yellow" };
			} else {
				return { score, label: "Strong", color: "green" };
			}
		},

		isPasswordTooCommon(password: string): boolean {
			const commonPasswords = [
				"password",
				"password123",
				"password123!",
				"12345678",
				"qwerty",
				"abc123",
				"monkey",
				"letmein",
				"trustno1",
				"dragon",
				"baseball",
				"iloveyou",
				"master",
				"sunshine",
				"ashley",
				"bailey",
				"passw0rd",
				"shadow",
				"123123",
				"654321",
				"superman",
				"qazwsx",
				"michael",
				"football",
			];

			return commonPasswords.includes(password.toLowerCase());
		},
	}));
});
