/**
 * SpeedX SPA Router
 * 
 * Handles client-side navigation with History API and fetch.
 * Zero dependencies, vanilla JavaScript.
 * 
 * @package SpeedX
 * @version 2.0.0
 */

class SpeedXRouter {
	constructor() {
		this.contentContainer = document.getElementById( 'content-container' );
		this.progressBar = document.getElementById( 'sx-progress-bar' );
		this.loader = document.getElementById( 'sx-loader' );
		this.isTransitioning = false;
		
		this.init();
	}

	init() {
		// Intercept all internal links.
		document.addEventListener( 'click', this.handleLinkClick.bind( this ) );
		
		// Handle browser back/forward.
		window.addEventListener( 'popstate', this.handlePopState.bind( this ) );
		
		// Update progress bar on scroll.
		window.addEventListener( 'scroll', this.updateProgressBar.bind( this ), { passive: true } );
		
		// Initial load complete.
		this.hideLoader();
	}

	/**
	 * Handle link clicks for SPA navigation.
	 * @param {Event} event - Click event.
	 */
	handleLinkClick( event ) {
		const link = event.target.closest( 'a[href]' );
		
		if ( ! link || ! this.shouldIntercept( link ) ) {
			return;
		}
		
		event.preventDefault();
		
		const url = link.href;
		this.navigate( url );
	}

	/**
	 * Determine if a link should be intercepted.
	 * @param {HTMLAnchorElement} link - Link element.
	 * @return {boolean} True if should intercept.
	 */
	shouldIntercept( link ) {
		// Skip external links.
		if ( link.hostname !== window.location.hostname ) {
			return false;
		}
		
		// Skip admin links.
		if ( link.pathname.includes( '/wp-admin/' ) ) {
			return false;
		}
		
		// Skip hash-only links.
		if ( link.hash && link.pathname === window.location.pathname ) {
			return false;
		}
		
		// Skip links with data-no-spa attribute.
		if ( link.hasAttribute( 'data-no-spa' ) ) {
			return false;
		}
		
		return true;
	}

	/**
	 * Navigate to a new URL.
	 * @param {string} url - Target URL.
	 */
	async navigate( url ) {
		if ( this.isTransitioning ) {
			return;
		}
		
		this.isTransitioning = true;
		this.showLoader();
		
		try {
			const response = await this.fetchContent( url );
			const data = await response.json();
			
			if ( data.success ) {
				this.updateContent( data.html, data.title, url );
			} else {
				throw new Error( 'Failed to load content' );
			}
		} catch ( error ) {
			console.error( 'SpeedX Router Error:', error );
			window.location.href = url; // Fallback to full page load.
		} finally {
			this.isTransitioning = false;
		}
	}

	/**
	 * Fetch content from REST API.
	 * @param {string} url - URL to fetch.
	 * @return {Promise<Response>} Fetch response.
	 */
	fetchContent( url ) {
		return fetch( speedxAjax.restUrl + '?url=' + encodeURIComponent( url ), {
			headers: {
				'X-WP-Nonce': speedxAjax.restNonce,
				'X-SpeedX-SPA': 'true',
			},
		} );
	}

	/**
	 * Update page content.
	 * @param {string} html - New HTML content.
	 * @param {string} title - New page title.
	 * @param {string} url - New URL.
	 */
	updateContent( html, title, url ) {
		// Fade out.
		this.contentContainer.style.opacity = '0';
		this.contentContainer.style.transition = 'opacity 0.3s ease';
		
		setTimeout( () => {
			// Update DOM.
			this.contentContainer.innerHTML = html;
			document.title = title;
			
			// Update browser history.
			history.pushState( { path: url }, '', url );
			
			// Fade in.
			this.contentContainer.style.opacity = '1';
			
			// Reinitialize any scripts/events.
			this.afterLoad();
			
			this.hideLoader();
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		}, 300 );
	}

	/**
	 * Handle browser back/forward navigation.
	 * @param {PopStateEvent} event - PopState event.
	 */
	handlePopState( event ) {
		this.navigate( window.location.href );
	}

	/**
	 * Actions to run after content loads.
	 */
	afterLoad() {
		// Animate elements with fade-up class.
		const elements = document.querySelectorAll( '.fade-up' );
		elements.forEach( ( el, index ) => {
			el.style.animationDelay = `${index * 0.1}s`;
		} );
		
		// Reset progress bar.
		this.progressBar.style.width = '0%';
	}

	/**
	 * Update reading progress bar.
	 */
	updateProgressBar() {
		const scrollTop = window.scrollY;
		const docHeight = document.documentElement.scrollHeight - window.innerHeight;
		const progress = ( scrollTop / docHeight ) * 100;
		
		this.progressBar.style.width = `${progress}%`;
	}

	/**
	 * Show loading indicator.
	 */
	showLoader() {
		if ( speedxAjax.loadingText !== 'false' ) {
			this.loader.classList.add( 'active' );
		}
	}

	/**
	 * Hide loading indicator.
	 */
	hideLoader() {
		this.loader.classList.remove( 'active' );
	}
}

// Initialize router when DOM is ready.
document.addEventListener( 'DOMContentLoaded', () => {
	new SpeedXRouter();
} );
