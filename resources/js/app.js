import './bootstrap';

// Expose Community API client to the window for Blade/Livewire pages
import * as CommunityAPI from './api/communities';

if (window.axios) {
  // Ensure cookies are sent for Sanctum session auth
  window.axios.defaults.withCredentials = true;
}

// Initialize CSRF cookie early so mutating API calls work
CommunityAPI.ensureCsrfCookie().catch(() => {
  // Swallow errors silently; pages may retry on demand
});

// Make available globally for inline scripts
window.CommunityAPI = CommunityAPI;
