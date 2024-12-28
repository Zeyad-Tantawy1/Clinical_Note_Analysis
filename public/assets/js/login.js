// public/assets/js/login.js

class LoginValidator {
    constructor() {
        this.form = document.getElementById('loginForm');
        this.email = document.getElementById('email');
        this.password = document.getElementById('password');
        this.emailError = document.getElementById('emailError');
        this.passwordError = document.getElementById('passwordError');
        
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        this.form.addEventListener('submit', (e) => this.validateForm(e));
        
        // Real-time validation
        this.email.addEventListener('input', () => this.validateEmailRealTime());
        this.password.addEventListener('input', () => this.validatePasswordRealTime());
        
        // Validate on blur (when user leaves the field)
        this.email.addEventListener('blur', () => this.validateEmailRealTime());
        this.password.addEventListener('blur', () => this.validatePasswordRealTime());
    }

    validateForm(e) {
        let isValid = true;

        // Reset error messages
        this.clearAllErrors();

        // Email validation
        if (!this.validateEmail()) {
            isValid = false;
        }

        // Password validation
        if (!this.validatePassword()) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    }

    validateEmailRealTime() {
        const email = this.email.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            this.showError(this.emailError, 'Email is required');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            this.showError(this.emailError, 'Please enter a valid email address');
            return false;
        }

        this.clearError(this.emailError);
        return true;
    }

    validatePasswordRealTime() {
        const password = this.password.value.trim();

        if (!password) {
            this.showError(this.passwordError, 'Password is required');
            return false;
        }

        if (password.length < 6) {
            this.showError(this.passwordError, 'Password must be at least 6 characters');
            return false;
        }

        this.clearError(this.passwordError);
        return true;
    }

    validateEmail() {
        const email = this.email.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            this.showError(this.emailError, 'Email is required');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            this.showError(this.emailError, 'Please enter a valid email address');
            return false;
        }

        return true;
    }

    validatePassword() {
        const password = this.password.value.trim();

        if (!password) {
            this.showError(this.passwordError, 'Password is required');
            return false;
        }

        if (password.length < 6) {
            this.showError(this.passwordError, 'Password must be at least 6 characters');
            return false;
        }

        return true;
    }

    showError(element, message) {
        element.textContent = message;
        element.style.visibility = 'visible';
        element.parentElement.querySelector('input').classList.add('invalid');
    }

    clearError(element) {
        element.textContent = '';
        element.style.visibility = 'hidden';
        element.parentElement.querySelector('input').classList.remove('invalid');
    }

    clearAllErrors() {
        this.clearError(this.emailError);
        this.clearError(this.passwordError);
    }
}

// Initialize the validator when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LoginValidator();
});