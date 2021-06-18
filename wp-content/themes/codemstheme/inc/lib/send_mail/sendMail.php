<?php 
/**
 * Class SendMail
 * Description: Personnalise les courriels envoyé par WordPress.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.6
 *
 *
 * @category Addons
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SendMail' ) ) :
	
/**
 * Personnalise les courriels envoyé par WordPress.
 *
 * @category Addons
 * @author Codems
 * @version 1.0.0
 */	
final class SendMail {
	
	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;
	
	/**
	 * Active le mode débogage.
	 * @var Debug
	 */
	public $debug = false;
	
	/**
	 * Rend la Classe compatible avec WooCommerce.
	 * @var Woo
	 */
	private $woo = false;
	
	
	/**
	 * Initialise l'instance de la Classe.
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @static
	 * @return SendMail - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Constructeur de la Classe.
	 */
	public function __construct() {
		if( class_exists('WooCommerce') )
			$this->woo = true;
		
		$this->init_hooks();
		$this->includes();
	}
	
	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
		
		add_filter( 'wp_mail', array($this, 'add_email_style') );
		add_filter( 'wp_mail_from', array($this, 'the_mail_from') );
		add_filter( 'wp_mail_from_name', array($this, 'the_mail_from_name') );
		
		add_filter('woocommerce_mail_content', array($this, 'add_styled_tag') );
	}
	
	
	/**
	 * Inclu les fichiers principaux.
	 */
	public function includes() {
		// Crop class
		if(!$this->woo)
			include_once( __DIR__.'/inc/Emogrifier.php' );
		
	}
	
	
	/**
	 * Déclanche la Classe lorsque WordPress est initialisé.
	 */
	public function init() {
		
		
	}
	
	
	
	/**
	 * Localise un template de courriel et retourne le chemin.
	 * 
	 * @param String $template_name
	 * @param Bool $load
	 * 
	 * @return String
	 */
	private function locate_template( $template_name, $load = false ){
		$located = '';
		if( file_exists(__DIR__. '/' .$template_name) ){
			$located = __DIR__. '/' .$template_name;
		}
		
		if( $load && $located != '' ){
			include( $located );
		}
		
		return $located;
	}
	
	
	/**
	 * Retourne un template. (email/template-$templateName)
	 *
	 * @param String $template Nom du template.
	 * @param Array $data Variable à passer au template.
	 * 
	 * @return HTML|Bool
	 */
	public function get_template($template, $data = array()){
		if($templateName = $this->locate_template('templates/'.$template.'.php')){
			if(!empty($data) && is_array($data)){
				extract($data);
			}
			
			ob_start();
			include($templateName);
			return ob_get_clean();
		}
		
		return false;
		
	}
	
	
	
	/**
	 * Envoie le courriel.
	 * 
	 * @param Array $email
	 * @param Array $args
	 * @return Bool
	 */
	public function send($email = array(), $args = array()){
		$defaults = array(
			'to' => '',
			'subject' => '',
			'message' => '',
			'headers' => 'Content-Type: text/html; charset=UTF-8',
			'attachments' => '',
			'template' => 'default'
		);
		
		$email = wp_parse_args( $email, $defaults );
		
		if($email['to'] == '' || $email['subject'] == '')
			return false;
		
		$content = $this->build_template($email['template'], $email['message'], $args);
		
		if($this->debug){
			echo $content;
			exit();
		}else{
			$content = $this->add_styled_tag($content);
			return wp_mail($email['to'], $email['subject'], $content, $email['headers'], $email['attachments']);
		}
		
	}// send
	
	
	
	/**
	 * Assemble le courriel pour l'envoie.
	 * 
	 * @param Array $email
	 * @return Array
	 */
	public function add_email_style($email){
		if(strpos($email['message'], '|styled|') !== false){
			$email['message'] = str_replace('|styled|', '', $email['message']);
			
		}else{
			$emailContent = make_clickable($email['message']);
			
			$emailContent = str_replace('<<a', '<a', $emailContent);
			$emailContent = str_replace('</a>>', '</a>', $emailContent);
			
			$message = str_replace('</p>', '</p><br>', $emailContent);
			
			$content = $this->build_template('default', nl2br($emailContent));
			
			$header = $email['headers'];
			if(is_string($header)){
				if($header == ''){
					$header = 'Content-Type: text/html; charset=UTF-8';
					
				}else if(strpos($header, 'text/plain')){
					$header = str_replace('text/plain', 'text/html', $header);
				}
				
				$headers = explode("\n", $header);
			}
			
			$headers = array_filter($headers);
			
			$email = array(
				'to'          => $email['to'],
				'subject'     => $email['subject'],
				'message'     => $content,
				'headers'     => $headers,
				'attachments' => $email['attachments'],
			);
		}
		
		return $email;
	}// add_email_style
	
	
	
	/**
	 * Monte le HTML du courriel.
	 * 
	 * @param String $template
	 * @param String $content
	 * @param Array $args
	 * @return HTML
	 */
	private function build_template($template = 'default', $content = '', $args = array()){
		if($this->woo){		
			$emailTemplates = $this->get_woo_template($content, $args);
		}else{
			$args['content'] = $content;
			$emailTemplates = $this->get_template('header', $args);
			$emailTemplates .= $this->get_template($template, $args);
			$emailTemplates .= $this->get_template('footer', $args);
			
			$emailTemplates = $this->inline_style($emailTemplates);
		}
		
		return $emailTemplates;
	}// build_template
	
		
	
	/**
	 * Retourne le courriel monté avec WooCommerce.
	 * 
	 * @param String $content
	 * @param Array $args
	 * @return HTML
	 */
	public function get_woo_template($content, $args = array()){
	    ob_start();
	    
		wc_get_template('emails/email-header.php', $args);
		echo $content;
		wc_get_template( 'emails/email-footer.php' );
		
		$content = ob_get_clean();
		
		return $content;
	}// get_woo_template
	
	
	
	/**
	 * Ajoute le style en inline dans le HTML du courriel.
	 * 
	 * @see Emogrifier
	 * @param String $html
	 * @return HTML
	 */
	public function inline_style($html){
		// bail early if woocommerce is actif
		if($this->woo)
			return $html;
		
		$html = str_replace('</p>', '</p><br>', $html); // add <br> after <p> tags because margin is set to 0
		
		$stylePath = $this->locate_template('templates/email-styles.php');
		ob_start();		
		include($stylePath);
		$css = ob_get_clean();

		// apply CSS styles inline
		try {
			$emogrifier = new Emogrifier( $html, $css );
			$html = $emogrifier->emogrify();
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
		
		return $html;
	}// inline_style
	
	
	
	/**
	 * Ajoute un tag pour savoir si le template a été stylé.
	 * 
	 * @param String $message
	 * @return String
	 */
	public function add_styled_tag($message){
		$message .= '|styled|';
		return $message;
	}// add_woo_tag
	
	
	/**
	 * Change le mail From de tous les emails.
	 * 
	 * @return String
	 */
	public function the_mail_from() {
	    return get_bloginfo('admin_email');
	}// the_mail_from
	
	
	
	/**
	 * Change le mail From name de tous les emails.
	 * 
	 * @return String
	 */
	public function the_mail_from_name() {
	    return get_bloginfo('name');
	}// the_mail_from
	
	
	
}// SendMail Class
	
endif;

/**
 * Retourne l'instance de la Classe.
 * 
 * @see SendMail
 */
function SendMail() {
	return SendMail::instance();
}

// Accessible aussi par une global
$GLOBALS['SendMail'] = SendMail();

?>