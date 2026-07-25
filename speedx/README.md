# SpeedX Theme - Documentation

## Overview
SpeedX is an ultra-lightweight, neumorphic-styled Single Page Application (SPA) WordPress theme. It features partial page reloads without full refreshes, zero JavaScript dependencies, and a beautiful soft UI design language.

## Features

### Core Features
- **SPA Navigation**: Pages load instantly without full browser refresh using History API
- **Neumorphic Design**: Beautiful soft UI with raised and pressed elements
- **Zero Dependencies**: No jQuery, Vue, React, or build tools required
- **Performance Optimized**: Critical CSS inlining, resource hints, script deferment
- **SEO Friendly**: Server-side rendering with proper meta tags
- **Accessibility**: Semantic HTML5, ARIA labels, keyboard navigation support
- **Responsive Design**: Mobile-first approach with system fonts

### Technical Specifications
- **File Size**: ~108KB total
- **Lines of Code**: ~2,000 lines
- **JavaScript**: Vanilla ES6+ (~300 lines)
- **CSS**: Custom properties with neumorphic design system
- **PHP**: WordPress coding standards compliant

## Installation

1. Upload the `speedx` folder to `/wp-content/themes/`
2. Activate via WordPress Admin → Appearance → Themes
3. Create a primary menu via Appearance → Menus
4. Navigate your site - links now load without page refresh!

## Customization

### Theme Customizer
Go to **Appearance → Customize** to configure:

#### Colors
- **Primary Color**: Accent color for links and buttons

#### SPA Settings
- **Enable SPA Mode**: Toggle partial page loads on/off
- **Loading Animation**: Choose between progress bar, spinner, or none
- **Transition Speed**: Adjust fade animation duration (0-2 seconds)

### Neumorphic Design System

The theme uses CSS custom properties for easy customization:

```css
:root {
    --bg-color: #e0e5ec;        /* Main background */
    --text-main: #4a5568;       /* Primary text */
    --text-muted: #a0aec0;      /* Secondary text */
    --accent: #3b82f6;          /* Accent color */
    --shadow-light: #ffffff;    /* Light shadow source */
    --shadow-dark: #a3b1c6;     /* Dark shadow source */
}
```

### Utility Classes

Use these classes in your templates:

- `.neu-raised` - Elevated elements with hover effects
- `.neu-pressed` - Inset shadows for inputs/active states
- `.neu-flat` - Flat elements with rounded corners
- `.neu-circle` - Circular buttons with depth
- `.btn-neu` - Neumorphic button style
- `.article-card` - Card layout for posts

## Architecture

### File Structure
```
speedx/
├── style.css              # Main stylesheet with design system
├── functions.php          # Theme setup, REST API, Customizer
├── header.php             # Persistent header wrapper
├── footer.php             # Persistent footer wrapper
├── index.php              # Blog listing template
├── single.php             # Single post template
├── page.php               # Page template
├── archive.php            # Archive template
├── search.php             # Search results template
├── 404.php                # Error page template
├── comments.php           # Comments template
├── searchform.php         # Search form template
├── template-functions.php # Helper functions
└── assets/
    └── js/
        └── router.js      # SPA router
```

### How SPA Works

1. **Initial Load**: Full page rendered server-side for SEO
2. **Link Interception**: Router captures internal link clicks
3. **API Request**: Fetches HTML fragment via REST API endpoint
4. **Content Swap**: Replaces `#content-container` content
5. **History Update**: Updates browser URL using History API
6. **Smooth Transition**: Fade animation between states

### REST API Endpoint

**Endpoint**: `GET /wp-json/speedx/v1/fragment`

**Parameters**:
- `url` (required): Target URL to fetch
- `template` (optional): Template type (auto-detected if not provided)

**Response**:
```json
{
    "content": "<div>...</div>",
    "title": "Page Title",
    "meta_description": "Page description..."
}
```

### Template Detection

The router automatically detects the appropriate template:
- Date URLs (`/2024/01/...`) → `single.php`
- Archive URLs (`/category/`, `/tag/`, `/author/`) → `archive.php`
- Search URLs (`?s=query`) → `search.php`
- Homepage → `index.php`

## Performance Optimizations

### Implemented Strategies

1. **Critical CSS Inlining**: Above-the-fold styles in `<head>`
2. **Resource Hints**: Preconnect to CDN domains
3. **Script Deferment**: JS loaded in footer with `defer`
4. **Asset Minimization**: No unused WordPress scripts/styles
5. **Lazy Loading**: Images use native lazy loading
6. **System Fonts**: No external font requests

### Removed Elements

- Emoji detection scripts
- WordPress default jQuery
- Block library CSS (unless needed)
- XML-RPC (security + performance)
- Generator meta tags

## Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

**Note**: Older browsers gracefully degrade to traditional navigation.

## Accessibility

- Semantic HTML5 elements
- ARIA labels on interactive elements
- Keyboard navigation support
- Focus indicators
- Skip-to-content link
- Proper heading hierarchy
- Color contrast compliance

## Security Features

- Template whitelist validation
- Nonce verification ready
- Input sanitization throughout
- XSS protection via escaping
- XML-RPC disabled
- WordPress version hidden

## Extending SpeedX

### Adding Custom Templates

Create new template files following the existing pattern:

```php
<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="content-wrapper fade-enter-active">
    <!-- Your content here -->
</div>

<?php
get_footer();
```

### Disabling SPA for Specific Links

Add `no-spa` class to exclude links from SPA behavior:

```html
<a href="/external-page" class="no-spa">External Link</a>
```

### Programmatic Navigation

Use the exposed router function:

```javascript
window.SpeedXRouter.navigate('/your-page');
```

### Custom Events

Listen for navigation completion:

```javascript
window.addEventListener('speedx:navigation-complete', (e) => {
    console.log('Navigated to:', e.detail.url);
});
```

## Troubleshooting

### SPA Not Working

1. Check browser console for JavaScript errors
2. Verify REST API is enabled in WordPress
3. Ensure permalinks are set to "Post name" or custom structure
4. Check that `speedx_enable_spa` is true in Customizer

### Content Not Loading

1. Verify REST API endpoint is accessible: `/wp-json/speedx/v1/fragment?url=...`
2. Check server error logs
3. Ensure template files exist and are readable

### Styling Issues

1. Clear browser cache
2. Check for CSS conflicts with plugins
3. Verify child theme (if used) properly enqueues parent styles

## Best Practices

1. **Keep Templates Lightweight**: Avoid heavy queries in templates
2. **Use Native Lazy Loading**: Add `loading="lazy"` to images
3. **Optimize Images**: Use WebP format when possible
4. **Minimize Inline Styles**: Use CSS custom properties
5. **Test Without JavaScript**: Ensure graceful degradation

## License

GNU General Public License v2 or later

## Credits

Developed with ❤️ for performance enthusiasts who demand beautiful design without compromising speed.
