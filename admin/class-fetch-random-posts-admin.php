<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fetch_Random_Posts
 * @subpackage Fetch_Random_Posts/admin
 * @author     Olivija Guzelyte <o.guzelyte@gmail.com>
 */
class Fetch_Random_Posts_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        /* Add plugin admin menu, register and build fields */
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'), 9);
        add_action('admin_init', array($this, 'register_and_build_fields'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Fetch_Random_Posts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Fetch_Random_Posts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/fetch-random-posts-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Fetch_Random_Posts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Fetch_Random_Posts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/fetch-random-posts-admin.js', array('jquery'), $this->version, false);

    }

    public function add_plugin_admin_menu()
    {
        add_menu_page($this->plugin_name, 'Fetch Random Posts', 'administrator', $this->plugin_name, array($this, 'display_plugin_admin_dashboard'), 'dashicons-admin-page', 26);

        add_submenu_page($this->plugin_name, 'Fetch Random Posts Settings', 'Settings', 'administrator', $this->plugin_name . '-settings', array($this, 'display_plugin_admin_settings'));
    }

    public function display_plugin_admin_dashboard()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display.php';
    }

    public function register_and_build_fields()
    {

        add_settings_section(
            'fetch_random_posts_general_section',
            '',
            array($this, 'plugin_name_display_general_account'),
            'fetch_random_posts_general_settings'
        );
        unset($args);
        /* Build max posts field args array */
        $args_max_plugin_setting = array(
            'type' => 'input',
            'subtype' => 'number',
            'id' => 'frp_random_posts_max_posts',
            'name' => 'frp_random_posts_max_posts',
            'required' => 'required',
            'get_options_list' => '',
            'value_type' => 'normal',
            'wp_data' => 'option',
            'min' => '1',
            'max' => '100',
        );
        /* Build order field args array */
        $args_max_plugin_setting_order = array(
            'type' => 'select',
            'id' => 'frp_random_posts_order',
            'name' => 'frp_random_posts_order',
            'required' => 'required',
            'values' => array('Ascdending' => 'ascending', 'Descending' => 'descending'),
            'get_options_list' => '',
            'value_type' => 'normal',
            'wp_data' => 'option',
        );
        /* Add max posts field */
        add_settings_field(
            'frp_random_posts_max_posts',
            'Choose default posts number:',
            array($this, 'fetch_random_posts_render_settings_field'),
            'fetch_random_posts_general_settings',
            'fetch_random_posts_general_section',
            $args_max_plugin_setting
        );
        /* Add order field */
        add_settings_field(
            'frp_random_posts_order',
            'Choose default ordering:',
            array($this, 'fetch_random_posts_render_settings_field'),
            'fetch_random_posts_general_settings',
            'fetch_random_posts_general_section',
            $args_max_plugin_setting_order
        );
        /* Register max posts field */
        register_setting(
            'fetch_random_posts_general_settings',
            'frp_random_posts_max_posts',
        );
        /* Register order field */
        register_setting(
            'fetch_random_posts_general_settings',
            'frp_random_posts_order',
        );
    }

    public function plugin_name_display_general_account()
    {
        echo '<p>These settings apply to all Fetch Random Posts functionality.</p>';
    }

    public function fetch_random_posts_render_settings_field($args)
    {

        if ($args['wp_data'] == 'option') {
            $wp_data_value = get_option($args['name']);
        } elseif ($args['wp_data'] == 'post_meta') {
            $wp_data_value = get_post_meta($args['post_id'], $args['name'], true);
        }
        /* Build the HTML of input and select fields */
        switch ($args['type']) {

            case 'input':
                $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
                if ($args['subtype'] != 'checkbox') {
                    $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
                    $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                    $step = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
                    $min = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
                    $max = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';
                    if (isset($args['disabled'])) {
                        // hide the actual input bc if it was just a disabled input the informaiton saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                        echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    } else {
                        echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" ' . $args['required'] . ' ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                    }
                } else {
                    $checked = ($value) ? 'checked' : '';
                    echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
                }
                break;
            case 'select':
                echo $selected;
                echo '<select ' . 'id="' . $args['id'] . '" ' . 'name="' . $args['name'] . '" />';
                foreach ($args['values'] as $index => $option) {
                    $selected = $wp_data_value == $option ? 'selected' : ' ';
                    echo '<option ' . $selected . ' value="' . $option . '">' . $index . '</option>';
                }
                echo '</select>';
                break;
            default:
                break;
        }
    }

    public function display_plugin_admin_settings()
    {
        // set this var to be used in the settings-display view
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, 'plugin_name_settings_messages'));
            do_action('admin_notices', $_GET['error_message']);
        }
        require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
    }

    public function plugin_name_settings_messages($error_message)
    {
        switch ($error_message) {
            case '1':
                $message = __('There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'o.guzelyte@gmail.com');
                $err_code = esc_attr('plugin_name_example_setting');
                $setting_field = 'plugin_name_example_setting';
                break;
        }
        $type = 'error';
        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            $type
        );
    }

}
