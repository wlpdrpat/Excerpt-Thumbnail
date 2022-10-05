<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wellplanet.com
 * @since      1.0.0
 *
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/admin
 * @author     Patrick Coleman <pat@wellplanet.com>
 */
class Excerpt_Thumbnail_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $excerpt_thumbnail    The ID of this plugin.
	 */
	private $excerpt_thumbnail;

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
	 * @param      string    $excerpt_thumbnail       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $excerpt_thumbnail, $version ) {

		$this->excerpt_thumbnail = $excerpt_thumbnail;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Excerpt_Thumbnail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Excerpt_Thumbnail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->excerpt_thumbnail, plugin_dir_url( __FILE__ ) . 'css/excerpt-thumbnail-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Excerpt_Thumbnail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Excerpt_Thumbnail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->excerpt_thumbnail, plugin_dir_url( __FILE__ ) . 'js/excerpt-thumbnail-admin.js', array( 'jquery' ), $this->version, false );

	}

}
