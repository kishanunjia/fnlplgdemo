<?php 
/**
 * Expiring Page
 *
 * @package       ExpiringPage
 * @author        ExpiringPage 
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Expiring Page
 * Description:   Expiring Page Plugin.
 * Version:       1.0.0
 * Author:        -
 * Author URI:    
 * Text Domain:   expiring-page
 * Domain Path:   /languages 
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *  
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EXPIRINGP_PLUGIN', __FILE__ );
define( 'EXPIRINGP_PLUGIN_BASENAME', plugin_basename( EXPIRINGP_PLUGIN ));

class ExpiringPage {

  public $passdata = array();	

  public function __construct(){
    add_action( 'activate_' . EXPIRINGP_PLUGIN_BASENAME, [$this, 'EXPIRINGP_plugin_activate'], 10, 0 );
    $this->add_plugin_settings();
    if( !is_admin() ){
    	add_action( 'wp',[$this,'redirect'], 1 );
    }
  }

  public function load() {
    add_action('admin_menu', [$this, 'expiringp_admin_menu']);
    add_action('init',[$this,'callback_expiringp_front_scripts']);
    add_shortcode('expiringp_countdown_timer',[$this,'expiringp_countdown_timer_fun']);
  }

  public function callback_expiringp_front_scripts(){
    wp_register_script( 'expiringp_countdown_js', plugins_url('/assets/front/js/expiringp-countdown.js', __FILE__));
    wp_enqueue_style('expiringp_countdown_style',plugins_url('/assets/front/css/expiringp-countdown.css', __FILE__));
  }

  public function custom_meta_add(){

  	$passdata = $this->passdata;
  	if(!empty($passdata)){
  	$useURL     = (isset($passdata['useURL']))?$passdata['useURL']:'';
  	$time_after = (int)(isset($passdata['time_after']))?$passdata['time_after']:'';

  	?>
  	 <script> var time_after = <?=$time_after;?>; setTimeout(function(){ window.location.href = '<?=$useURL;?>'; },time_after);	</script>
  	<?php
  	}
  }



  /* The code for active plugin */
  public function EXPIRINGP_plugin_activate(){
    
    /* Create Table When Active Plugin */    
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . 'expiring_page_data';
    if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS `${table_name}` (
                    `epd_id` INT(200) NOT NULL AUTO_INCREMENT,
                    `page_id` INT(200) NOT NULL ,
                    `page_type` VARCHAR(200) NOT NULL ,
                    `red_page_id` INT(200) NOT NULL ,
                    `red_page_time` VARCHAR(200) NOT NULL ,
                    `red_type` VARCHAR(200) NOT NULL ,
                    `create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`epd_id`)) ENGINE = InnoDB;";
        dbDelta($sql);  
    }

  } 

  public function add_timer_on_the_page($content){

    if(is_page()){
      $timer_data = $this->expiringp_countdown_timer_fun();
      if(!empty($timer_data)){  
        $options    = get_option( 'expiringp_options' );
        $countdown_timer_position  = (isset($options['countdown_timer_position']))?$options['countdown_timer_position']:'';
        if($countdown_timer_position == 'bottom'){
          return $content.$timer_data;     
        }else{
          return $timer_data.$content; 
        }  
      }
    }
    return $content;
  }

  public function expiringp_countdown_timer_fun(){

    global $post;
    $current_id = (isset($post->ID))?$post->ID:'';
    if($current_id){
      global $wpdb;
      $table_name = $wpdb->prefix . 'expiring_page_data';
      $current_time = strtotime(wp_date('Y-m-d H:i:s'));
      $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name Where page_id = %d AND red_page_time > %d", $current_id, $current_time), ARRAY_A);
      if(!empty($data)){

        $red_page_time = $data[0]['red_page_time'];
        wp_enqueue_script('jquery');
        wp_enqueue_script('expiringp_countdown_js');
        wp_enqueue_script('expiringp_countdown_style');
        $final_time = wp_date('Y/m/d H:i:s',$red_page_time);

        $datetime_1 = wp_date('Y-m-d H:i:s',$red_page_time); 
        $datetime_2 = wp_date('Y-m-d H:i:s'); 
        $start_datetime = new DateTime($datetime_1); 
        $diff = $start_datetime->diff(new DateTime($datetime_2)); 

        $days  = $diff->d;
        $hours = $diff->h;
        $mins  = $diff->i;
        $sec   = $diff->s;

        $current_time = strtotime(wp_date('Y-m-d H:i:s'));
        $time_after    = ($red_page_time - $current_time);        

        ob_start();
        $options    = get_option( 'expiringp_options' );
        $countdown_timer_title_text  = (isset($options['countdown_timer_title_text']))?$options['countdown_timer_title_text']:'';
        $countdown_timer_bottom_text  = (isset($options['countdown_timer_bottom_text']))?$options['countdown_timer_bottom_text']:'';

        $countdown_timer_text_color  = (isset($options['countdown_timer_text_color']))?$options['countdown_timer_text_color']:'#000000';
        $countdown_timer_background_color  = (isset($options['countdown_timer_background_color']))?$options['countdown_timer_background_color']:'#e2e2e2';
        $countdown_timer_label_color  = (isset($options['countdown_timer_label_color']))?$options['countdown_timer_label_color']:'#666666';
        $countdown_time_end_text  = (isset($options['countdown_time_end_text']))?$options['countdown_time_end_text']:'Time is ended';

        $countdown_timer_title_size      = (isset($options['countdown_timer_title_size']))?$options['countdown_timer_title_size']:33;
        $countdown_timer_text_size       = (isset($options['countdown_timer_text_size']))?$options['countdown_timer_text_size']:55;
        $countdown_timer_label_size      = (isset($options['countdown_timer_label_size']))?$options['countdown_timer_label_size']:14;
        $countdown_timer_bottomtxt_size  = (isset($options['countdown_timer_bottomtxt_size']))?$options['countdown_timer_bottomtxt_size']:20;

        $countdown_timer_title_font_url  = (isset($options['countdown_timer_title_font_url']))?$options['countdown_timer_title_font_url']:'';
        $countdown_timer_title_font      = (isset($options['countdown_timer_title_font']))?$options['countdown_timer_title_font']:'';

        $countdown_timer_text_font_url  = (isset($options['countdown_timer_text_font_url']))?$options['countdown_timer_text_font_url']:'';
        $countdown_timer_text_font      = (isset($options['countdown_timer_text_font']))?$options['countdown_timer_text_font']:'';

        $countdown_timer_bottom_text_font_url  = (isset($options['countdown_timer_bottom_text_font_url']))?$options['countdown_timer_bottom_text_font_url']:'';
        $countdown_timer_bottom_text_font      = (isset($options['countdown_timer_bottom_text_font']))?$options['countdown_timer_bottom_text_font']:'';        

        $import_font = '';
        $title_font_family = '';
        $text_font_family  = '';
        $bottom_text_font_family  = '';

        if(!empty($countdown_timer_title_font_url) && !empty($countdown_timer_title_font)){
          $import_font.= $countdown_timer_title_font_url;
          $title_font_family = 'font-family:'.$countdown_timer_title_font.' !important;';
        }

        if(!empty($countdown_timer_text_font_url) && !empty($countdown_timer_text_font)){
          $import_font.= $countdown_timer_text_font_url;
          $text_font_family = 'font-family:'.$countdown_timer_text_font.' !important;';
        }

        if(!empty($countdown_timer_bottom_text_font_url) && !empty($countdown_timer_bottom_text_font)){
          $import_font.= $countdown_timer_bottom_text_font_url;
          $bottom_text_font_family = 'font-family:'.$countdown_timer_bottom_text_font.' !important;';
        }

        echo '
        <style>
          '.$import_font.'
          .countdown.hero_count{
            background-color: '.$countdown_timer_background_color.';
          }
          .hero_count h2 {
             font-size:'.$countdown_timer_title_size.'px;
             '.$title_font_family.'
          }
          .countdown.show .running timer, .countdown.hero_count .running timer{
             font-size:'.$countdown_timer_text_size.'px; 
             '.$text_font_family.'
          }
          .hero_count .labels span{
             font-size:'.$countdown_timer_label_size.'px;  
             '.$text_font_family.'
          }
          .countdown.hero_count .running .text, .timr-b-txt{
            font-size:'.$countdown_timer_bottomtxt_size.'px;
            '.$bottom_text_font_family.'
            color: '.$countdown_timer_text_color.' !important;   
          }
          .countdown.hero_count .running timer, .hero_count h2, .timr-b-txt{
            color: '.$countdown_timer_text_color.' !important;
          }  
          .timr-labels span, timr-b-txt{ 
            color: '.$countdown_timer_label_color.' !important;
          }
        </style>
        ';
    ?>
         <div class="timer-main">
          <div class="countdown hero_count" data-fixTime='{"Days": "<?php echo $days; ?>", "Hours": "<?php echo $hours; ?>", "Minutes": "<?php echo $mins; ?>", "Seconds": "<?php echo $sec; ?>"}'>
              <?php if(!empty($countdown_timer_title_text)){ echo "<h2>".$countdown_timer_title_text."</h2>"; } ?>
              <div class="running">
                  <timer>
                      <span class="days"></span>:<span class="hours"></span>:<span
                          class="minutes"></span>:<span class="seconds"></span>
                  </timer>
                  <div class="break"></div>
                  <div class="labels timr-labels">
                      <span><?php echo __('Days','expiring-page'); ?></span><span><?php echo __('Hours','expiring-page'); ?></span><span><?php echo __('Minutes','expiring-page'); ?></span><span><?php echo __('Seconds','expiring-page'); ?></span>
                  </div>
                  <div class="break"></div>
                  <?php if(!empty($countdown_timer_bottom_text)){ echo '<div class="text timr-b-txt">'.$countdown_timer_bottom_text.'</div>';  } ?>
                  <div class="break"></div>
              </div>
              <div class="ended">
                  <div class="text"><?php echo $countdown_time_end_text; ?></div>
              </div>
          </div>
         </div> 
      <?php
    }

    $data = ob_get_clean();
    return $data;

    }

    

  }

  /* The code for add a admin menu */
  public function expiringp_admin_menu(){

	$expiringp_setting = add_menu_page(''.__( 'Expiring Page','expiring-page'),''.__( 'Expiring Page','expiring-page'),'activate_plugins',
		'expiring-page',[$this, 'expiring_setting_page_callback'],'dashicons-admin-settings',4);

    add_submenu_page('expiring-page', __('Expiring Page', 'expiring-page'), __('Expiring Page', 'expiring-page'), 'activate_plugins', 'expiring-page', [$this, 'expiring_setting_page_callback']);

    // The code for add new expiring page menu
    $expiringp_add_edit_setting = add_submenu_page('expiring-page', __('Add new', 'expiring-page'), __('Add new', 'expiring-page'), 'activate_plugins', 'expiring_form', [$this, 'expiring_data_add_callback']);

    // The code for add expiring page settings menu
    $expiringp_extra_setting = add_submenu_page('expiring-page', __('Settings', 'expiring-page'), __('Settings', 'expiring-page'), 'manage_options', 'expiringp_form', [$this, 'expiring_extra_settings_callback']);    

	  add_action( 'load-' . $expiringp_setting, [$this, 'adminscript_enqueue'] );
	  add_action( 'load-' . $expiringp_add_edit_setting, [$this, 'adminscript_enqueue'] );
	  add_action( 'load-' . $expiringp_extra_setting, [$this, 'adminscript_enqueue'] );

  }

  /* The code for add a admin CSS & JS */
  public function expiring_extra_settings_callback(){
  	
    /* The code for add plugin settings in admin side */
    echo '<form action="options.php" method="post">';
    // output security fields for the registered setting "wporg"
    settings_fields( 'expiringp' );
    // output setting sections and their fields
    do_settings_sections( 'expiringp' );
    // output save settings button
    submit_button( __('Save Settings','expiring-page') );
    echo '</form>';

  }

  /* The code for add a admin CSS & JS */
  public function adminscript_enqueue(){

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

	  wp_enqueue_style('expiringp_admin_style', plugins_url('/assets/admin/css/admin-style.css', __FILE__));
	  wp_enqueue_script('expiringp_admin_style');

	  wp_register_style('datetimepicker-expiringp', plugins_url('/assets/admin/css/jquery.datetimepicker.css', __FILE__));
	  wp_enqueue_style('datetimepicker-expiringp');

    wp_register_script( 'datetimepicker-expiringp-js', plugins_url('/assets/admin/js/jquery.datetimepicker.js', __FILE__));
    wp_enqueue_script('datetimepicker-expiringp-js');

    wp_register_script( 'custom-expiringp-js', plugins_url('/assets/admin/js/custom.js', __FILE__));
    wp_enqueue_script('custom-expiringp-js');



  }

  /* The code for admin expiring setting page */
  public function expiring_setting_page_callback(){ 	
  	require_once( plugin_dir_path( __FILE__ ) . '/include/expiring-page-settings.php');
  }

  /* The code for add expiring page */
  public function expiring_data_add_callback(){ 	
  	 require_once( plugin_dir_path( __FILE__ ) . '/include/add-expiring-page-settings.php');
  }

  /* The code for redirect page */
  public function redirect(){


	  if (is_page()) {

	  		global $wpdb;
	  		$table_name = $wpdb->prefix . 'expiring_page_data';
	  		$curpage_id = get_the_ID();
	  		$current_time = strtotime(wp_date('Y-m-d H:i:s'));
			$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name Where page_id = %d AND red_page_time < %d", $curpage_id, $current_time), ARRAY_A);
			if(!empty($data)){

				$red_page_id = $data[0]['red_page_id'];
				$red_type    = $data[0]['red_type'];
				$useURL      = get_the_permalink($red_page_id);
				header('RedirectType: Expiring Page Plugin Redirect');
				wp_redirect( $useURL, $red_type );
				exit();
					
			}else{

				$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name Where page_id = %d AND red_page_time > %d", $curpage_id, $current_time), ARRAY_A);	
				if(!empty($data)){
					
					$red_page_time = $data[0]['red_page_time'];
					$red_page_id   = $data[0]['red_page_id'];
					$useURL        = get_the_permalink($red_page_id);
					$time_after    = ($red_page_time - $current_time)*1000;
					$passdata = array('useURL'=>$useURL,'time_after'=>$time_after);
					$this->passdata = $passdata;
					add_action('wp_head', [$this,'custom_meta_add'],'10');
					//do_action( 'wp_head',$passdata);


				}
				
			}
	  }	

  }


  /* The code for add extra admin setting fields */
  public function add_plugin_settings(){
  	require_once( plugin_dir_path( __FILE__ ) . '/include/expiring-page-extra-settings.php');
  } 

}

//Call Expiring Page Module
$plugin = new ExpiringPage();
$plugin->load();