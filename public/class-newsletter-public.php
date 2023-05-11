<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://avinash.wisdmlabs.net/
 * @since      1.0.0
 *
 * @package    Newsletter
 * @subpackage Newsletter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Newsletter
 * @subpackage Newsletter/public
 * @author     Avinash Jha <avinash.jha@wisdmlabs.com>
 */
class Newsletter_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/newsletter-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/newsletter-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name, 
			'newsletter_ajax', 
			array( 
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce( 'newsletter_ajax_nonce' )
			)
		);
	
	}
	 function newsletter_form_shortcode ($atts){
		$atts = shortcode_atts(array('title' => 'Subscribe NewsLetter'), $atts, 'sendmail_shortcode');
		ob_start();
		?>
		<div class="sendmail-shortcode">
			<h3 class="form-heading"><?php echo $atts['title'] ?></h3>
			<form id="sendmail-email-form" method='post'>
				<input type="email" name="email" placeholder="Enter your email address" required>
				<input type="submit" name="submit"/>
			</form>
		</div>
		<div id="form-response"></div>

		<?php
			$output = ob_get_contents();
			ob_get_clean();
			return $output;
	 }
	
	 function email_form_submit(){
		if(check_ajax_referer('newsletter_ajax_nonce', '_ajax_nonce') && isset($_POST['email'])){
			$email = sanitize_email($_POST['email']);
			
			$subscribed_mails = get_option('newsletter_subscribers', array());
			
			if (in_array($email, $subscribed_mails)) {
				$response = json_encode(array("email" => $email, "message" => "Already Subscribed"));
			}else{
				$subscribed_mails[] = $email;
				update_option('newsletter_subscribers', $subscribed_mails);
				
				$response = json_encode(array("email" => $email,"message" => "Successfully Subscribed"));
			}
			$this->mailer($email);
			echo $response;
			wp_die();
		}
	
		wp_die();	
	 }
	 function mailer($email){
		$site_title = get_bloginfo( 'name' );
		$number_of_posts = get_option('newsletter_numbers',0);
		$subject = "Hurray!! Welcome to ". $site_title ;
		$message = "<h3>You are Successfully subscribed for the daily updates of " . $site_title . "</h3>";
		$message .= "<br>";
		$message .= "<br>";
		$message .= "<h4>Here are our latest " . $number_of_posts . " Posts</h4>";
		$message .= "<br>";

		$summary = $this->get_latest_posts($number_of_posts);
		foreach ($summary as $post_data) {
			$message .= 'Title: ' . $post_data['title'] . "\n<br>";
			$message .= 'URL: <a>' . $post_data['url'] . "</a>\n<br>";
			$message .= "<br>\n";
		}
		
		$headers = array(
			'From: avinash.jha@wisdmlabs.com',
			'Content-Type: text/html; charset=UTF-8'
		);

		wp_mail($email, $subject, $message, $headers);

	 }
	 function get_latest_posts($number_of_posts){
		$args = array(
			'post_type' => 'post',
			'posts_per_page'=> $number_of_posts,
			'post_status'=> 'publish'
		);

		$query = new WP_Query($args);
		$posts = $query->posts;
		$summary = array();

		foreach($posts as $post){
			$post_data = array(
				'title' => $post->post_title,
				'url' => get_permalink($post->ID),
			);
			array_push($summary, $post_data);
		}
		return $summary;
	 }

}
?>