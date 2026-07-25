/**
 * SpeedX SPA Router
 * Handles partial page loads using History API and Fetch
 * 
 * @package SpeedX
 */

(function() {
'use strict';

// Configuration
const config = {
contentSelector: '#content-container',
loaderSelector: '#sx-loader',
progressSelector: '#sx-progress-bar',
apiEndpoint: window.speedxConfig ? window.speedxConfig.apiUrl : '/wp-json/speedx/v1/fragment',
nonce: window.speedxConfig ? window.speedxConfig.nonce : '',
};

// State
let isLoading = false;
let currentUrl = window.location.href;

/**
 * Initialize the router
 */
function init() {
setupEventListeners();
setupProgressObserver();
setupMobileNav();
console.log('SpeedX SPA Router initialized');
}

/**
 * Setup global event listeners
 */
function setupEventListeners() {
// Intercept all clicks on internal links
document.addEventListener('click', handleGlobalClick, true);

// Handle browser back/forward buttons
window.addEventListener('popstate', handlePopState);

// Scroll progress
window.addEventListener('scroll', updateScrollProgress);
}

/**
 * Handle global click events for SPA navigation
 * @param {Event} e - Click event
 */
function handleGlobalClick(e) {
const link = e.target.closest('a[href]');

if (!link) return;

const href = link.getAttribute('href');

// Skip external links, mailto, tel, anchors, admin links
if (
href.startsWith('#') ||
href.startsWith('mailto:') ||
href.startsWith('tel:') ||
href.includes('//') && !href.includes(window.location.hostname) ||
href.includes('/wp-admin') ||
href.includes('/wp-login.php')
) {
return;
}

// Check if user is using modifier keys
if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) {
return;
}

e.preventDefault();
navigateTo(href);
}

/**
 * Navigate to a new URL via SPA
 * @param {string} url - Target URL
 */
async function navigateTo(url) {
if (isLoading) return;
if (url === currentUrl) return;

isLoading = true;
showLoader();

try {
const data = await fetchFragment(url);

if (!data.success) {
throw new Error('Failed to load content');
}

// Update content
updateContent(data.html);

// Update title
if (data.title) {
document.title = data.title + ' | ' + document.querySelector('.site-title')?.textContent?.trim() || '';
}

// Update browser history
history.pushState({ url: url }, '', url);
currentUrl = url;

// Scroll to top
window.scrollTo({ top: 0, behavior: 'smooth' });

// Reinitialize any dynamic components
reinitComponents();

} catch (error) {
console.error('SPA Navigation error:', error);
window.location.href = url; // Fallback to full page load
} finally {
isLoading = false;
hideLoader();
}
}

/**
 * Fetch HTML fragment from API
 * @param {string} url - URL to fetch
 * @returns {Promise<Object>}
 */
async function fetchFragment(url) {
const params = new URLSearchParams({ url: url });
if (config.nonce) {
params.append('_wpnonce', config.nonce);
}

const response = await fetch(`${config.apiEndpoint}?${params}`, {
method: 'GET',
headers: {
'Accept': 'application/json',
'X-Requested-With': 'XMLHttpRequest',
},
});

if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}

return await response.json();
}

/**
 * Update the content container with new HTML
 * @param {string} html - New HTML content
 */
function updateContent(html) {
const container = document.querySelector(config.contentSelector);
if (!container) return;

// Fade out
container.style.opacity = '0';
container.style.transition = 'opacity 0.2s ease';

setTimeout(() => {
container.innerHTML = html;

// Fade in
container.style.opacity = '1';

// Announce to screen readers
const announcer = document.createElement('div');
announcer.setAttribute('aria-live', 'polite');
announcer.className = 'sr-only';
announcer.textContent = 'Content updated';
container.appendChild(announcer);

setTimeout(() => announcer.remove(), 1000);
}, 200);
}

/**
 * Handle browser back/forward navigation
 * @param {PopStateEvent} event
 */
async function handlePopState(event) {
if (isLoading) return;

const url = window.location.href;
if (url === currentUrl) return;

await navigateTo(url);
}

/**
 * Show loading indicator
 */
function showLoader() {
const loader = document.querySelector(config.loaderSelector);
if (loader) {
loader.style.display = 'flex';
loader.style.opacity = '1';
}
}

/**
 * Hide loading indicator
 */
function hideLoader() {
const loader = document.querySelector(config.loaderSelector);
if (loader) {
setTimeout(() => {
loader.style.opacity = '0';
setTimeout(() => {
loader.style.display = 'none';
}, 300);
}, 300);
}
}

/**
 * Update reading progress bar based on scroll position
 */
function updateScrollProgress() {
const progressBar = document.querySelector(config.progressSelector);
if (!progressBar) return;

const scrollTop = window.scrollY;
const docHeight = document.documentElement.scrollHeight - window.innerHeight;
const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

progressBar.style.width = `${Math.min(scrollPercent, 100)}%`;
}

/**
 * Setup intersection observer for scroll-reveal animations
 */
function setupProgressObserver() {
if ('IntersectionObserver' in window) {
const observer = new IntersectionObserver((entries) => {
entries.forEach(entry => {
if (entry.isIntersecting) {
entry.target.classList.add('revealed');
}
});
}, { threshold: 0.1 });

document.querySelectorAll('.post-card, .hero, .widget').forEach(el => {
el.style.opacity = '0';
el.style.transform = 'translateY(20px)';
el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
observer.observe(el);
});
}
}

/**
 * Setup mobile navigation toggle
 */
function setupMobileNav() {
const hamburger = document.getElementById('hamburger-toggle');
const drawer = document.getElementById('mobile-nav-drawer');

if (!hamburger || !drawer) return;

hamburger.addEventListener('click', () => {
const isActive = drawer.classList.toggle('active');
hamburger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
drawer.setAttribute('aria-hidden', isActive ? 'false' : 'true');
});

// Close drawer when clicking outside
document.addEventListener('click', (e) => {
if (!hamburger.contains(e.target) && !drawer.contains(e.target)) {
drawer.classList.remove('active');
hamburger.setAttribute('aria-expanded', 'false');
drawer.setAttribute('aria-hidden', 'true');
}
});
}

/**
 * Reinitialize dynamic components after content swap
 */
function reinitComponents() {
// Reset scroll progress
updateScrollProgress();

// Re-setup any components that need it
setupProgressObserver();

// Fire custom event for other scripts to hook into
window.dispatchEvent(new CustomEvent('speedx:content-loaded'));
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', init);
} else {
init();
}
})();
