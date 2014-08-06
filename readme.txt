=== Site Logo ===
Contributors: kwight, automattic
Requires at least: 3.0.1
Tested up to: 4.0alpha
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a logo to your WordPress site. Set it once, and all themes that support it will display it automatically.

== Installation ==

1. Upload the `site-logo` folder to the `/wp-content/plugins/` directory, or check out the plugin from GitHub: `git clone git@github.com:Automattic/site-logo.git`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add support by adding `add_theme_support( 'site-logo' );` to the theme's setup function.
4. Use the `the_site_logo()` template tag in `header.php` to display the logo on the front-end.

== Use ==

Activating the plugin and adding support in a theme allows the user to upload a logo through the Customizer (the uploader can be found in the Site Title & Tagline section). Display is determined entirely by the theme.

The `add_theme_support()` declaration can take a `size` argument. The default is `thumbnail`, with other valid values being `medium`, `large`, `full`, and any additional sizes declared by `add_image_size`. If the selected logo is not big enough to have the requested size, 'full' will be used on output instead.

`add_theme_support` also takes a `header-text` argument. This is an array of classes (without the leading `.`) that should be hidden with the "Display Header Text" setting. Defaults to the same classes as Underscores: `site-title` and `site-description`.
