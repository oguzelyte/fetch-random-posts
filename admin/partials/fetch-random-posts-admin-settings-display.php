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

<!-- This file contains the form of the settings page. -->
<div class="wrap">
	 <h2>Fetch Random Posts Plugin Settings</h2>
		<?php settings_errors();?>
		<form method="POST" action="options.php">
			<?php
				settings_fields('fetch_random_posts_general_settings');
				do_settings_sections('fetch_random_posts_general_settings');
			?>
		    <?php submit_button();?>
		</form>
</div>