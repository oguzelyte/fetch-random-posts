<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       test
 * @since      1.0.0
 *
 * @package    Fetch_Random_Posts
 * @subpackage Fetch_Random_Posts/admin/partials
 */
?>

<!-- This file displays some example usage for the plugin. -->

<h2> Random Posts Display Plugin Usage: </h2>

<div class="frp-example">
<h3>Example 1 </h3>
<p>Displays random posts based on the settings values (default ordering is ascending).</p>
<code> [random_posts] </code>
</div>

<div class="frp-example">
<h3>Example 2 </h3>
<p> Displays random posts based on the settings values, but the ordering is overwritten by either 'asc' or 'desc' values within the shortcode.</p>
<code> [random_posts order="asc"] </code>
</div>

<div class="frp-example">
<h3>Example 3 </h3>
<p>Displays random posts based on the settings values, but the amount of posts displayed is defined by the 'count' value within the shortcode.</p>
<code> [random_posts count="10"] </code>
<p> <b>Note: if the 'count' value is bigger than the amount of posts found, then the maximum amounts of posts is displayed. </b></p>
</div>

<div class="frp-example">
<h3>Example 4 </h3>
<p>Displays random posts based on the 'count' and 'order' shortcode values.</p>
<code> [random_posts order="asc" count="4"]</code>
</div>
