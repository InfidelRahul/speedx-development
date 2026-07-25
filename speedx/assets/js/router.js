/**
 * SpeedX SPA Router
 * 
 * Ultra-lightweight vanilla JavaScript router for partial page loads
 * Uses History API and Fetch for seamless navigation without page refreshes
 * 
 * @package SpeedX
 * @version 1.0.0
 */

(function() {
    'use strict';

    /**
     * Router Configuration
     */
    const config = {
        contentSelector: '#content-container',
        loaderSelector: '#page-loader',
        linkSelector: 'a[href^="' + window.location.origin + '"]:not([data-no-spa]):not([target="_blank"]):not([download])',
        formSelector: 'form[method="get"]',
        transitionSpeed: 400,
        showLoader: true,
    };

    /**
     * State management
     */
    let isLoading = false;
    let currentUrl = window.location.href;

    /**
     * Initialize the router
     */
    function init() {
        // Get settings from WordPress if available
        if (typeof speedxAjax !== 'undefined') {
            config.showLoader = speedxAjax.showLoader !== false;
            config.transitionSpeed = parseInt(speedxAjax.transitionSpeed) || 400;
        }

        // Attach event listeners
        attachEventListeners();

        // Handle browser back/forward
        window.addEventListener('popstate', handlePopState);

        console.log('SpeedX Router initialized');
    }

    /**
     * Attach all event listeners
     */
    function attachEventListeners() {
        // Delegate link clicks
        document.addEventListener('click', handleLinkClick, true);

        // Delegate form submissions (search forms)
        document.addEventListener('submit', handleFormSubmit, true);
    }

    /**
     * Handle link clicks for SPA navigation
     * @param {Event} event - Click event
     */
    function handleLinkClick(event) {
        // Find closest anchor tag
        const link = event.target.closest('a');
        
        if (!link || !link.matches(config.linkSelector)) {
            return;
        }

        // Ignore special clicks
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        // Get href
        const href = link.getAttribute('href');
        
        // Ignore hash-only links
        if (href === '#' || href.startsWith('#')) {
            return;
        }

        // Ignore same-page links
        if (href === currentUrl) {
            event.preventDefault();
            return;
        }

        // Prevent default and navigate via SPA
        event.preventDefault();
        navigateTo(href);
    }

    /**
     * Handle form submissions (search forms)
     * @param {Event} event - Submit event
     */
    function handleFormSubmit(event) {
        const form = event.target;
        
        if (!form.matches(config.formSelector)) {
            return;
        }

        event.preventDefault();

        // Build query string
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();
        
        // Navigate to search results
        const action = form.getAttribute('action') || window.location.origin;
        const url = action + (action.includes('?') ? '&' : '?') + queryString;
        
        navigateTo(url);
    }

    /**
     * Navigate to a new URL via SPA
     * @param {string} url - URL to navigate to
     */
    async function navigateTo(url) {
        if (isLoading) {
            return;
        }

        isLoading = true;
        currentUrl = url;

        // Show loader
        if (config.showLoader) {
            showLoader();
        }

        try {
            // Fetch the new content
            const path = url.replace(window.location.origin, '');
            const response = await fetch(`${speedxAjax.restUrl}?path=${encodeURIComponent(path)}`, {
                headers: {
                    'X-WP-Nonce': speedxAjax.nonce,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Update browser history
                history.pushState({ url: url }, '', url);

                // Update content with fade transition
                await updateContent(data.content, data.title);

                // Scroll to top
                window.scrollTo(0, 0);

                // Re-initialize any dynamic content
                reinitializeDynamicContent();
            } else {
                throw new Error('Failed to load content');
            }
        } catch (error) {
            console.error('SPA Navigation error:', error);
            // Fallback to full page load
            window.location.href = url;
        } finally {
            isLoading = false;
            hideLoader();
        }
    }

    /**
     * Handle browser back/forward buttons
     * @param {PopStateEvent} event - PopState event
     */
    function handlePopState(event) {
        if (isLoading) {
            return;
        }

        const url = window.location.href;
        
        if (url === currentUrl) {
            return;
        }

        currentUrl = url;
        isLoading = true;

        if (config.showLoader) {
            showLoader();
        }

        // Fetch the content for the current URL
        const path = url.replace(window.location.origin, '');
        
        fetch(`${speedxAjax.restUrl}?path=${encodeURIComponent(path)}`, {
            headers: {
                'X-WP-Nonce': speedxAjax.nonce,
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateContent(data.content, data.title);
                reinitializeDynamicContent();
            }
        })
        .catch(error => {
            console.error('PopState navigation error:', error);
            window.location.reload();
        })
        .finally(() => {
            isLoading = false;
            hideLoader();
        });
    }

    /**
     * Update page content with fade transition
     * @param {string} content - New HTML content
     * @param {string} title - New page title
     */
    function updateContent(content, title) {
        return new Promise((resolve) => {
            const container = document.querySelector(config.contentSelector);
            
            if (!container) {
                resolve();
                return;
            }

            // Fade out
            container.style.opacity = '0';
            container.style.transition = `opacity ${config.transitionSpeed}ms ease`;

            setTimeout(() => {
                // Update content
                container.innerHTML = content;
                
                // Update title
                if (title) {
                    document.title = title;
                }

                // Fade in
                container.style.opacity = '1';
                
                // Add fade-in animation class to new content
                const wrapper = container.querySelector('.content-wrapper');
                if (wrapper) {
                    wrapper.classList.add('fade-in');
                }

                resolve();
            }, config.transitionSpeed);
        });
    }

    /**
     * Show loading indicator
     */
    function showLoader() {
        let loader = document.querySelector(config.loaderSelector);
        
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'page-loader';
            loader.innerHTML = '<div class="loader-spinner"></div>';
            document.body.appendChild(loader);
        }

        loader.classList.add('active');
    }

    /**
     * Hide loading indicator
     */
    function hideLoader() {
        const loader = document.querySelector(config.loaderSelector);
        
        if (loader) {
            loader.classList.remove('active');
        }
    }

    /**
     * Reinitialize dynamic content after page load
     * This is where you can reinitialize any JS plugins or features
     */
    function reinitializeDynamicContent() {
        // Dispatch custom event for other scripts to hook into
        const event = new CustomEvent('speedxContentLoaded', {
            detail: { url: currentUrl }
        });
        document.dispatchEvent(event);

        // Re-attach event listeners to new content
        attachEventListeners();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
