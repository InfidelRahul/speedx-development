/**
 * SpeedX SPA Router
 * Ultra-lightweight vanilla JavaScript router for partial page loads
 * Zero dependencies, ~150 lines
 */

(function() {
    'use strict';

    // Configuration from WordPress
    const config = window.speedxConfig || {};
    const isEnabled = true; // Can be controlled via customizer

    // State
    let isLoading = false;
    let currentUrl = window.location.href;

    // DOM Elements
    const contentContainer = document.getElementById('content-container');
    const siteContent = document.getElementById('site-content');
    const loadingIndicator = document.getElementById('loading-indicator');
    const siteHeader = document.getElementById('site-header');
    const siteFooter = document.getElementById('site-footer');

    /**
     * Initialize the SPA router
     */
    function init() {
        if (!isEnabled || !contentContainer) {
            return;
        }

        // Intercept all internal links
        document.addEventListener('click', handleLinkClick, true);

        // Handle browser back/forward buttons
        window.addEventListener('popstate', handlePopState);

        // Mark all internal links as SPA links
        markSpaLinks();

        // Console log for debugging
        console.log('SpeedX SPA Router initialized');
    }

    /**
     * Mark all internal links with spa-link class
     */
    function markSpaLinks() {
        const links = document.querySelectorAll('a[href]');
        
        links.forEach(link => {
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
     */
    function handleLinkClick(event) {
        // Find closest anchor tag
        const link = event.target.closest('a');
        
        if (!link || !isEnabled || isLoading) {
            return;
        }

        const href = link.getAttribute('href');

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
     */
    async function navigateTo(url) {
        if (isLoading || url === currentUrl) {
            return;
        }

        isLoading = true;
        showLoading();

        try {
            // Update browser history
            history.pushState({ url: url }, '', url);

            // Fetch the new content
            const data = await fetchContent(url);

            // Update the page
            updatePage(data);

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });

            currentUrl = url;
        } catch (error) {
            console.error('Navigation failed:', error);
            // Fallback to full page reload on error
            window.location.href = url;
        } finally {
            isLoading = false;
            hideLoading();
        }
    }

    /**
     * Handle browser back/forward
     */
    async function handlePopState(event) {
        if (!isEnabled || isLoading) {
            return;
        }

        const url = window.location.href;

        if (url === currentUrl) {
            return;
        }

        isLoading = true;
        showLoading();

        try {
            const data = await fetchContent(url);
            updatePage(data);
            currentUrl = url;
        } catch (error) {
            console.error('PopState navigation failed:', error);
            window.location.reload();
        } finally {
            isLoading = false;
            hideLoading();
        }
    }

    /**
     * Fetch content from server
     */
    async function fetchContent(url) {
        // Determine template based on URL
        const template = getTemplateForUrl(url);

        // Use REST API for fragment loading
        const restUrl = config.restUrl + 'speedx/v1/fragment?template=' + encodeURIComponent(template) + '&path=' + encodeURIComponent(url);

        const response = await fetch(restUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-WP-Nonce': config.nonce || '',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch content: ' + response.status);
        }

        return await response.json();
    }

    /**
     * Determine which template to load based on URL
     */
    function getTemplateForUrl(url) {
        // Simple URL pattern matching
        // Can be extended for custom post types, archives, etc.

        const path = new URL(url, window.location.origin).pathname;

        // Single post/page
        if (path.match(/^\/\d{4}\/\d{2}\/\d{2}\/[^/]+\/$/) || 
            path.match(/^\/[^/]+\/[^/]+\/$/)) {
            return 'single';
        }

        // Category archive
        if (path.match(/^\/category\//)) {
            return 'category';
        }

        // Tag archive
        if (path.match(/^\/tag\//)) {
            return 'tag';
        }

        // Author archive
        if (path.match(/^\/author\//)) {
            return 'author';
        }

        // Search results
        if (path.match(/^\/search\//) || url.includes('s=')) {
            return 'search';
        }

        // 404
        // This would need server-side detection

        // Default to index (blog/home)
        return 'index';
    }

    /**
     * Update page content
     */
    function updatePage(data) {
        if (!data || !data.content) {
            throw new Error('No content received');
        }

        // Fade out
        if (siteContent) {
            siteContent.classList.add('loading');
        }

        // Wait for transition
        setTimeout(() => {
            // Update content
            if (contentContainer && data.content) {
                contentContainer.innerHTML = data.content;
            }

            // Update document title
            if (data.title) {
                document.title = data.title + ' - ' + (config.siteName || '');
            }

            // Fade in
            if (siteContent) {
                siteContent.classList.remove('loading');
            }

            // Re-mark SPA links in new content
            markSpaLinks();

            // Dispatch custom event for other scripts
            document.dispatchEvent(new CustomEvent('speedx:navigation-complete', {
                detail: { url: currentUrl, data: data }
            }));
        }, 150); // Half of transition time
    }

    /**
     * Show loading indicator
     */
    function showLoading() {
        if (loadingIndicator) {
            loadingIndicator.classList.add('active');
        }
    }

    /**
     * Hide loading indicator
     */
    function hideLoading() {
        if (loadingIndicator) {
            loadingIndicator.classList.remove('active');
        }
    }

    /**
     * Public API for manual navigation
     */
    window.SpeedXRouter = {
        navigate: navigateTo,
        refresh: () => navigateTo(currentUrl),
        isEnabled: () => isEnabled,
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
