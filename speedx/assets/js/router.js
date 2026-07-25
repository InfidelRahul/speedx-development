/**
 * SpeedX SPA Router
 * 
 * Ultra-lightweight vanilla JavaScript router for partial page loads.
 * Implements History API for seamless navigation without page refreshes.
 * 
 * @package SpeedX
 * @version 1.0.0
 */

(function() {
    'use strict';

    /**
     * Router configuration
     */
    const CONFIG = {
        contentSelector: '#content-container',
        loaderSelector: '#spa-loader',
        linkSelector: 'a[href^="' + window.location.origin + '"]:not([target="_blank"]):not([download]):not(.no-spa)',
        formSelector: 'form[method="get"]',
        transitionDuration: 300,
        apiEndpoint: '/wp-json/speedx/v1/fragment',
    };

    /**
     * Router state management
     */
    const state = {
        isLoading: false,
        currentUrl: window.location.href,
        historyIndex: 0,
    };

    /**
     * DOM Elements cache
     */
    let elements = {};

    /**
     * Initialize the router
     */
    function init() {
        cacheElements();
        bindEvents();
        handleInitialLoad();
        console.log('SpeedX Router initialized');
    }

    /**
     * Cache frequently accessed DOM elements
     */
    function cacheElements() {
        elements = {
            content: document.querySelector(CONFIG.contentSelector),
            loader: document.querySelector(CONFIG.loaderSelector),
            title: document.title,
        };
    }

    /**
     * Bind event listeners
     */
    function bindEvents() {
        // Delegate click events for links
        document.addEventListener('click', handleClick, true);
        
        // Handle browser back/forward
        window.addEventListener('popstate', handlePopState);
        
        // Handle search forms
        document.addEventListener('submit', handleFormSubmit, true);
    }

    /**
     * Handle link clicks
     * @param {Event} e - Click event
     */
    function handleClick(e) {
        const link = e.target.closest(CONFIG.linkSelector);
        
        if (!link || !link.href) {
            return;
        }

        // Check if URL is internal and should use SPA
        const url = new URL(link.href);
        const isInternal = url.origin === window.location.origin;
        const isSamePath = url.pathname === window.location.pathname;
        
        if (!isInternal || isSamePath) {
            return;
        }

        e.preventDefault();
        navigateTo(link.href);
    }

    /**
     * Handle form submissions (search)
     * @param {Event} e - Submit event
     */
    function handleFormSubmit(e) {
        const form = e.target.closest(CONFIG.formSelector);
        
        if (!form) {
            return;
        }

        e.preventDefault();
        
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();
        const action = form.action || window.location.origin;
        const url = action + '?' + queryString;
        
        navigateTo(url);
    }

    /**
     * Navigate to a new URL
     * @param {string} url - Target URL
     * @param {boolean} pushState - Whether to push to history
     */
    function navigateTo(url, pushState = true) {
        if (state.isLoading || url === state.currentUrl) {
            return;
        }

        state.isLoading = true;
        showLoader();

        // Update browser history
        if (pushState) {
            history.pushState({ url: url }, '', url);
        }

        // Fetch new content with full URL for REST API
        fetchContent(url)
            .then((data) => {
                updatePage(data);
                state.currentUrl = url;
                scrollToTop();
            })
            .catch((error) => {
                console.error('Navigation error:', error);
                window.location.href = url; // Fallback to full page load
            })
            .finally(() => {
                state.isLoading = false;
                hideLoader();
            });
    }

    /**
     * Handle browser back/forward navigation
     * @param {PopStateEvent} e - PopState event
     */
    function handlePopState(e) {
        if (e.state && e.state.url) {
            navigateTo(e.state.url, false);
        }
    }

    /**
     * Fetch content from server
     * @param {string} url - URL to fetch
     * @returns {Promise<Object>} - Response data
     */
    function fetchContent(url) {
        // Determine template based on URL pattern
        let template = 'index';
        
        if (url.includes('/page/') || url.includes('/category/') || url.includes('/tag/') || url.includes('/author/')) {
            template = 'archive';
        } else if (url.includes('?s=')) {
            template = 'search';
        } else if (url.match(/\/\d{4}\/\d{2}\//)) {
            template = 'single';
        } else if (url === window.location.origin + '/' || url === window.location.origin) {
            template = 'index';
        }
        
        const apiUrl = CONFIG.apiEndpoint + '?template=' + encodeURIComponent(template) + '&url=' + encodeURIComponent(url);
        
        return fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        });
    }

    /**
     * Update page content
     * @param {Object} data - Response data with content and title
     */
    function updatePage(data) {
        if (!elements.content) {
            return;
        }

        // Fade out
        elements.content.style.opacity = '0';
        elements.content.style.transform = 'translateY(10px)';

        setTimeout(() => {
            // Update content
            elements.content.innerHTML = data.content || '';
            
            // Update title
            if (data.title) {
                document.title = data.title;
                elements.title = data.title;
            }

            // Update meta description if available
            if (data.meta_description) {
                let metaDesc = document.querySelector('meta[name="description"]');
                if (!metaDesc) {
                    metaDesc = document.createElement('meta');
                    metaDesc.name = 'description';
                    document.head.appendChild(metaDesc);
                }
                metaDesc.content = data.meta_description;
            }

            // Fade in
            elements.content.classList.add('fade-enter-active');
            elements.content.style.opacity = '1';
            elements.content.style.transform = 'translateY(0)';

            // Rebind events for new content
            setTimeout(() => {
                elements.content.classList.remove('fade-enter-active');
            }, CONFIG.transitionDuration);

            // Dispatch custom event for other scripts
            window.dispatchEvent(new CustomEvent('speedx:navigation-complete', { 
                detail: { url: state.currentUrl } 
            }));
        }, CONFIG.transitionDuration / 2);
    }

    /**
     * Show loading indicator
     */
    function showLoader() {
        if (elements.loader) {
            elements.loader.classList.add('active');
        }
    }

    /**
     * Hide loading indicator
     */
    function hideLoader() {
        if (elements.loader) {
            elements.loader.classList.remove('active');
        }
    }

    /**
     * Scroll to top of page
     */
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }

    /**
     * Handle initial page load animations
     */
    function handleInitialLoad() {
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.content-wrapper');
            if (wrapper) {
                wrapper.classList.add('fade-enter-active');
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose navigate function globally for programmatic navigation
    window.SpeedXRouter = {
        navigate: navigateTo,
    };

})();
