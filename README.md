=== Fetch Random Posts ===
Contributors: Olivija Guzelyte
Donate link: test
Tags: posts, fetch, random
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This is a plugin that fetches posts from https://jsonplaceholder.typicode.com/posts and then displays them based on either setting default values or what you specify within the shortcode attributes.

== Example Usage ==

**Example 1**
Displays random posts based on the settings values (default ordering is ascending).
`[random_posts]`

**Example 2**
Displays random posts based on the settings values, but the ordering is overwritten by either 'asc' or 'desc' values within the shortcode.
`[random_posts order="asc"]`

**Example 3**
Displays random posts based on the settings values, but the amount of posts displayed is defined by the 'count' value within the shortcode.
`[random_posts count="10"]`
Note: if the 'count' value is bigger than the amount of posts found, then the maximum amounts of posts is displayed.

**Example 4**
Displays random posts based on the 'count' and 'order' shortcode values.
`[random_posts order="asc" count="4"]`


== Installation ==

1. Upload `fetch-random-posts.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `[random_posts]` shortcode in your posts
4. Use `count` or `order` as shortcode attributes
