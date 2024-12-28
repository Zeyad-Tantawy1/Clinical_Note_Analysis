// public/assets/js/signup.js

class SignupValidator {
    constructor() {
        this.form = document.getElementById('signupForm');
        this.email = document.getElementById('email');
        this.password = document.getElementById('password');
        this.username = document.getElementById('username');
        
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        this.form.addEventListener('submit', (e) => this.validateForm(e));
        
        // Real-time validation
        this.email.addEventListener('input', () => this.validateEmailRealTime());
        this.password.addEventListener('input', () => this.validatePasswordRealTime());
        this.username.addEventListener('input', () => this.validateUsernameRealTime());
    }

    validateForm(e) {
        let isValid = true;

        if (!this.validateEmail()) isValid = false;
        if (!this.validatePassword()) isValid = false;
        if (!this.validateUsername()) isValid = false;

        if (!isValid) {
            e.preventDefault();
        }
    }

    validateEmailRealTime() {
        const email = this.email.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            this.showError(this.email, 'Email is required');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            this.showError(this.email, 'Please enter a valid email address');
            return false;
        }

        this.clearError(this.email);
        return true;
    }

    validatePasswordRealTime() {
        const password = this.password.value.trim();
        const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!password) {
            this.showError(this.password, 'Password is required');
            return false;
        }

        if (!passwordRegex.test(password)) {
            this.showError(this.password, 'Password must contain at least 8 characters, including uppercase, lowercase, number and special character');
            return false;
        }

        this.clearError(this.password);
        return true;
    }

    validateUsernameRealTime() {
        const username = this.username.value.trim();

        if (!username) {
            this.showError(this.username, 'Username is required');
            return false;
        }

        if (username.length < 3 || username.length > 20) {
            this.showError(this.username, 'Username must be between 3 and 20 characters');
            return false;
        }

        this.clearError(this.username);
        return true;
    }

    validateEmail() {
        return this.validateEmailRealTime();
    }

    validatePassword() {
        return this.validatePasswordRealTime();
    }

    validateUsername() {
        return this.validateUsernameRealTime();
    }

    showError(input, message) {
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = message;
        input.classList.add('invalid');
    }

    clearError(input) {
        const errorDiv = input.nextElementSibling;
        errorDiv.textContent = '';
        input.classList.remove('invalid');
    }
}

// Initialize the validator when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new SignupValidator();
});