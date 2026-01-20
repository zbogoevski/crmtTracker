// Global CRMTracker configuration and utilities

// Global CRMTracker object
window.CRMT = window.CRMT || {};

// CSRF token for AJAX requests (will be set by Blade template)
if (typeof window.CSRF_TOKEN === 'undefined') {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.CSRF_TOKEN = token.content;
    }
}

// API base URL (will be set by Blade template)
if (typeof window.API_BASE_URL === 'undefined') {
    window.API_BASE_URL = '/api';
}
