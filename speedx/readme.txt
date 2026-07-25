# SpeedX - Ultra-Lightweight SPA WordPress Theme

SpeedX is a blazing-fast WordPress theme that delivers a Single Page Application (SPA) experience without the bloat. Built with zero dependencies, it uses vanilla JavaScript for partial page loads, giving your site an app-like feel while maintaining WordPress's simplicity.

## Features

- **SPA Navigation**: Pages load without full refreshes using AJAX and the History API
- **Zero Dependencies**: No jQuery, no frameworks, no build process
- **Ultra-Lightweight**: Minimal CSS and JS for maximum performance
- **Customizer Panel**: Configure colors, SPA settings, and transitions
- **SEO Friendly**: Server-side rendering with client-side enhancements
- **Responsive Design**: Mobile-first, works on all devices
- **Accessibility Ready**: Semantic HTML and ARIA labels
- **Performance Optimized**: Critical CSS inlining, resource preloading

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Modern browser (Chrome, Firefox, Safari, Edge)

## Installation

1. Upload the `speedx` folder to `/wp-content/themes/`
2. Activate the theme through WordPress Admin > Appearance > Themes
3. Customize via Appearance > Customize

## Theme Structure

```
speedx/
├── style.css              # Main stylesheet with theme header
├── functions.php          # Theme setup and features
├── header.php             # Site header (persistent in SPA)
├── footer.php             # Site footer (persistent in SPA)
├── index.php              # Main template (blog/home)
├── single.php             # Single post template
├── page.php               # Page template
├── archive.php            # Archive templates (category, tag, etc.)
├── search.php             # Search results
├── 404.php                # Error page
├── searchform.php         # Search form
├── comments.php           # Comments template
├── template-functions.php # Helper functions
└── assets/
    ├── js/
    │   └── router.js      # SPA router (vanilla JS)
    └── css/
        └── (additional styles)
```

## How SPA Works

1. **Initial Load**: Full page loads normally for SEO
2. **Link Interception**: Router captures internal link clicks
3. **AJAX Fetch**: Content fetched via WordPress REST API
4. **Content Swap**: Only the main content area updates
5. **History Update**: Browser history updated without reload
6. **Smooth Transition**: CSS fade animations for polish

## Customizer Options

### SpeedX Colors
- Primary Color: Main accent color for links and buttons

### SPA Settings
- Enable SPA Mode: Toggle partial page loads on/off
- Loading Animation: Choose progress bar, spinner, or none
- Transition Speed: Adjust fade animation duration

## JavaScript API

Access the router programmatically:

```javascript
// Navigate to a URL
SpeedXRouter.navigate('/about');

// Refresh current page
SpeedXRouter.refresh();

// Check if SPA is enabled
SpeedXRouter.isEnabled();

// Listen for navigation complete
document.addEventListener('speedx:navigation-complete', function(e) {
    console.log('Navigated to:', e.detail.url);
});
```

## Disabling SPA for Specific Links

Add the `no-spa` class to any link to force full page load:

```html
<a href="/checkout" class="no-spa">Checkout</a>
```

Or wrap elements:

```html
<div class="no-spa">
    <a href="/external-action">This won't use SPA</a>
</div>
```

## Performance Tips

1. **Enable Caching**: Use a caching plugin like WP Super Cache
2. **Optimize Images**: Compress and serve WebP formats
3. **Minify Assets**: Use autoptimize or similar plugins
4. **CDN**: Serve static assets from a CDN
5. **HTTP/2**: Ensure your server supports HTTP/2

## Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

**Note**: Older browsers without Fetch API or History API support will gracefully degrade to normal navigation.

## Troubleshooting

### SPA not working?
- Check browser console for errors
- Ensure REST API is enabled
- Verify permalinks are set to "Post name"

### Content not updating?
- Clear browser cache
- Check if template files exist
- Verify REST API endpoint access

### Want to disable SPA temporarily?
- Go to Appearance > Customize > SPA Settings
- Uncheck "Enable SPA Mode"

## Development

To extend SpeedX:

1. **Add custom templates**: Create `template-{name}.php` files
2. **Extend router**: Modify `assets/js/router.js`
3. **Add styles**: Edit `style.css` or add files to `assets/css/`
4. **Custom REST endpoints**: Add to `functions.php`

## License

GPL v2 or later - http://www.gnu.org/licenses/gpl-2.0.html

## Credits

Built with ❤️ for speed enthusiasts.
