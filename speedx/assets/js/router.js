/**
 * SpeedX SPA Router
 * Ultra-lightweight vanilla JavaScript router for partial page loads
 * Zero dependencies, modular architecture
 * 
 * @package SpeedX
 * @version 1.0.0
 */

(function() {
    'use strict';

    /**
     * Module configuration
     */
    const Config = {
        enabled: true,
        ajaxUrl: '',
        restUrl: '',
        nonce: '',
        homeUrl: '',
        siteName: '',
        loadingType: 'bar',
        transitionSpeed: 0.3,
    };

    /**
     * Module state
     */
    const State = {
        isLoading: false,
        currentUrl: window.location.href,
    };

    /**
     * DOM element cache
     */
    const DOM = {
        contentContainer: null,
        siteContent: null,
        loadingIndicator: null,
        siteHeader: null,
        siteFooter: null,
    };

    /**
     * Initialize configuration from WordPress
     */
    function initConfig() {
        const config = window.speedxConfig || {};
        
        Config.enabled = config.spaEnabled !== undefined ? config.spaEnabled : true;
        Config.ajaxUrl = config.ajaxUrl || '';
        Config.restUrl = config.restUrl || '';
        Config.nonce = config.nonce || '';
        Config.homeUrl = config.homeUrl || '/';
        Config.siteName = config.siteName || '';
        Config.loadingType = config.loadingType || 'bar';
        Config.transitionSpeed = parseFloat(config.transitionSpeed) || 0.3;
    }

    /**
     * Cache DOM elements
     */
    function initDOM() {
        DOM.contentContainer = document.getElementById('content-container');
        DOM.siteContent = document.getElementById('site-content');
        DOM.loadingIndicator = document.getElementById('loading-indicator');
        DOM.siteHeader = document.getElementById('site-header');
        DOM.siteFooter = document.getElementById('site-footer');
    }

    /**
     * Initialize the SPA router
     */
    function init() {
        initConfig();
        initDOM();

        if (!Config.enabled || !DOM.contentContainer) {
            return;
        }

        // Intercept all internal links
        document.addEventListener('click', handleLinkClick, true);

        // Handle browser back/forward buttons
        window.addEventListener('popstate', handlePopState);

        // Mark all internal links as SPA links
        markSpaLinks();

        console.log('SpeedX SPA Router initialized');
    }

    /**
     * Mark all internal links with spa-link class
     */
    function markSpaLinks() {
        const links = document.querySelectorAll('a[href]');
        
        links.forEach(function(link) {
            const href = link.getAttribute('href');
            
            // Skip external links, anchors, and special URLs
            if (isExternalLink(href) || 
                href.startsWith('#') || 
                href.startsWith('mailto:') || 
                href.startsWith('tel:') ||
                link.classList.contains('no-spa') ||
                link.closest('.no-spa')) {
                return;
            }

            // Add spa-link class for styling
            link.classList.add('spa-link');
        });
    }

    /**
     * Check if a link is external
     * @param {string} url - URL to check
     * @returns {boolean} True if external
     */
    function isExternalLink(url) {
        try {
            const linkUrl = new URL(url, window.location.origin);
            return linkUrl.origin !== window.location.origin;
        } catch (e) {
            return true;
        }
    }

    /**
     * Handle link clicks
     * @param {Event} event - Click event
     */
    function handleLinkClick(event) {
        // Find closest anchor tag
        var link = event.target.closest('a');
        
        if (!link || !Config.enabled || State.isLoading) {
            return;
        }

        var href = link.getAttribute('href');

        // Skip special links
        if (!href || 
            href.startsWith('#') || 
            href.startsWith('mailto:') || 
            href.startsWith('tel:') ||
            link.classList.contains('no-spa') ||
            link.closest('.no-spa') ||
            link.getAttribute('target') === '_blank' ||
            isExternalLink(href)) {
            return;
        }

        // Prevent default navigation
        event.preventDefault();
        event.stopPropagation();

        // Navigate to the new page
        navigateTo(href);
    }

    /**
     * Navigate to a new URL
     * @param {string} url - Destination URL
     */
    async function navigateTo(url) {
        if (State.isLoading || url === State.currentUrl) {
            return;
        }

        State.isLoading = true;
        showLoading();

        try {
            // Update browser history
            history.pushState({ url: url }, '', url);

            // Fetch the new content
            var data = await fetchContent(url);

            // Update the page
            updatePage(data);

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });

            State.currentUrl = url;
        } catch (error) {
            console.error('Navigation failed:', error);
            // Fallback to full page reload on error
            window.location.href = url;
        } finally {
            State.isLoading = false;
            hideLoading();
        }
    }

    /**
     * Handle browser back/forward
     * @param {PopStateEvent} event - PopState event
     */
    async function handlePopState(event) {
        if (!Config.enabled || State.isLoading) {
            return;
        }

        var url = window.location.href;

        if (url === State.currentUrl) {
            return;
        }

        State.isLoading = true;
        showLoading();

        try {
            var data = await fetchContent(url);
            updatePage(data);
            State.currentUrl = url;
        } catch (error) {
            console.error('PopState navigation failed:', error);
            window.location.reload();
        } finally {
            State.isLoading = false;
            hideLoading();
        }
    }

    /**
     * Fetch content from server via REST API
     * @param {string} url - URL to fetch
     * @returns {Promise<Object>} Response data
     */
    async function fetchContent(url) {
        // Determine template based on URL
        var template = getTemplateForUrl(url);

        // Use REST API for fragment loading
        var restUrl = Config.restUrl + 'speedx/v1/fragment?template=' + encodeURIComponent(template) + '&path=' + encodeURIComponent(url);

        var response = await fetch(restUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-WP-Nonce': Config.nonce || '',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch content: ' + response.status);
        }

        return await response.json();
    }

    /**
     * Determine which template to load based on URL pattern
     * @param {string} url - URL to analyze
     * @returns {string} Template name
     */
    function getTemplateForUrl(url) {
        var path;
        
        try {
            path = new URL(url, window.location.origin).pathname;
        } catch (e) {
            return 'index';
        }

        // Single post/page patterns
        if (path.match(/^\/\d{4}\/\d{2}\/\d{2}\/[^/]+\/$/) || 
            path.match(/^\/[^/]+\/[^/]+\/$/)) {
            return 'single';
        }

        // Category archive
        if (path.match(/^\/category\//)) {
            return 'archive';
        }

        // Tag archive
        if (path.match(/^\/tag\//)) {
            return 'archive';
        }

        // Author archive
        if (path.match(/^\/author\//)) {
            return 'archive';
        }

        // Date archive
        if (path.match(/^\/\d{4}(\/\d{2})?(\/\d{2})?\/$/)) {
            return 'archive';
        }

        // Search results
        if (path.match(/^\/search\//) || url.indexOf('s=') !== -1) {
            return 'search';
        }

        // Default to index (blog/home)
        return 'index';
    }

    /**
     * Update page content with fade transition
     * @param {Object} data - Response data with content and title
     */
    function updatePage(data) {
        if (!data || !data.content) {
            throw new Error('No content received');
        }

        // Fade out
        if (DOM.siteContent) {
            DOM.siteContent.classList.add('loading');
        }

        // Wait for half of transition time
        setTimeout(function() {
            // Update content
            if (DOM.contentContainer && data.content) {
                DOM.contentContainer.innerHTML = data.content;
            }

            // Update document title
            if (data.title) {
                document.title = data.title + ' - ' + (Config.siteName || '');
            }

            // Fade in
            if (DOM.siteContent) {
                DOM.siteContent.classList.remove('loading');
            }

            // Re-mark SPA links in new content
            markSpaLinks();

            // Dispatch custom event for other scripts
            document.dispatchEvent(new CustomEvent('speedx:navigation-complete', {
                detail: { url: State.currentUrl, data: data }
            }));
        }, Config.transitionSpeed * 1000 / 2);
    }

    /**
     * Show loading indicator
     */
    function showLoading() {
        if (DOM.loadingIndicator) {
            DOM.loadingIndicator.classList.add('active');
        }
    }

    /**
     * Hide loading indicator
     */
    function hideLoading() {
        if (DOM.loadingIndicator) {
            DOM.loadingIndicator.classList.remove('active');
        }
    }

    /**
     * Public API for manual navigation
     */
    window.SpeedXRouter = {
        navigate: navigateTo,
        refresh: function() { return navigateTo(State.currentUrl); },
        isEnabled: function() { return Config.enabled; },
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
