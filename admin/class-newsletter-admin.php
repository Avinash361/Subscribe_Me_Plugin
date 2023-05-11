<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://avinash.wisdmlabs.net/
 * @since      1.0.0
 *
 * @package    Newsletter
 * @subpackage Newsletter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Newsletter
 * @subpackage Newsletter/admin
 * @author     Avinash Jha <avinash.jha@wisdmlabs.com>
 */
class Newsletter_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/newsletter-admin.css', array(), $this->version, 'all' );

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
		 * defined in Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/newsletter-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name, 
			'newsletter_setting_ajax', 
			array( 
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce( 'newsletter_ajax_nonce' )
			)
			);
	}

	
	 public function avi_newsletter_submenu_page(){

		add_submenu_page(
			'options-general.php',
			'NewsLetter',
			'News Letter',
			'manage_options',
			'avi_newsletter',
			array($this, 'submenu_page_callback'),
			
		);
	 }
	 public function submenu_page_callback(){
		?>
		<div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
      
		<form id="setting_form" method='post'>
			<label for="no_of_posts">No Of Posts: </label>
			<input type="text" name="no_of_posts" required>
			<input type="submit" name="submit"/>
		</form>
    </div>
	<?php
	

	 }
	 function setting_form_submit(){
		if(check_ajax_referer('newsletter_ajax_nonce', '_ajax_nonce') && isset($_POST['no_of_posts'])){
			$no_of_posts = $_POST['no_of_posts'];

			update_option('newsletter_numbers',$no_of_posts);
			$response = json_encode(array("no_of_posts" => $no_of_posts,"message" => "Successfully Updated"));
			echo $response;
			die();
		}
		$response = json_encode(array("no_of_posts" => 2,"message" => "Default Value"));
		echo $response;
		die();
	 }
}

