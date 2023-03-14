<?php
/*
Plugin Name:  Test Plugin
Plugin URI:   https://www.testplugin.com 
Description:  Test Plugin. 
Version:      1.0
Author:       test 
Author URI:   https://www.testplugin.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-tutorial
Domain Path:  /languages
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TESTP_PLUGIN', __FILE__ );
define( 'TESTP_PLUGIN_BASENAME', plugin_basename( TESTP_PLUGIN ));

/*
class SamplePage {

  public $passdata = array();	

  public function __construct(){
    add_action( 'activate_' . EXPIRINGP_PLUGIN_BASENAME, [$this, 'EXPIRINGP_plugin_activate'], 10, 0 );
    $this->add_plugin_settings();
    if( !is_admin() ){
    	add_action( 'wp',[$this,'redirect'], 1 );
    }
  }

}

//Call Expiring Page Module
$plugin = new ExpiringPage();
$plugin->load();

*/


add_action( 'activate_' . TESTP_PLUGIN_BASENAME, 'TESTP_plugin_activate', 10, 0 );

function TESTP_plugin_activate(){

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

/*METABOX CREATION*/
add_action( 'admin_init', 'product_info' );
function product_info() {
	add_meta_box( 'sub_title_club','Extra Data','display_sub_title_meta_box_prd','product', 'normal', 'high' );
}

/*sub title*/
function display_sub_title_meta_box_prd( $clubs_sub_title ) {	
	
	$extra = esc_html(get_post_meta($clubs_sub_title->ID,'extra', true));

?>

<table class="escort-profile-admin">
<tr>
<td style="width: 30%">Extra</td>
<td>
<td><input type="text" name="extra" id="extra" value="<?php echo $extra; ?>" /></td>
</td>
</tr>
</table>

<?php }

add_action( 'save_post','sub_title_club', 10, 2 );
function sub_title_club( $clubs_sub_title_id,$clubs_sub_title ) {
// Check post type for movie reviews
if ( $clubs_sub_title->post_type == 'clubs' ) {
// Store data in post meta table if present in post data

if ( isset( $_POST['extra'] ) ) {
	update_post_meta( $clubs_sub_title_id, 'extra',$_POST['extra'] );
}


}
}


// https://pluginrepublic.com/add-custom-cart-item-data-in-woocommerce/

//New Start Here..

//Add_cart item data



function plugin_republic_add_text_field() { ?>
 <div class="pr-field-wrap">
 <label for="pr-field"><?php _e( 'Your name', 'plugin-republic' ); ?></label>
 <input type="text" name='pr-field' id='pr-field' value=''>
 </div>
<?php }
add_action( 'woocommerce_before_add_to_cart_button', 'plugin_republic_add_text_field' );

function plugin_republic_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {
 if( empty( $_POST['pr-field'] ) ) {
 $passed = false;
 wc_add_notice( __( 'Your name is a required field.', 'plugin-republic' ), 'error' );
 }
 return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'plugin_republic_add_to_cart_validation', 10, 4 );


function add_custom_data_to_cart_item($cart_item_data, $product_id) {


	$extra = get_post_meta( $product_id, 'extra',true );



 	if(isset( $_POST['pr-field'] )){

    	$custom_data = array(
        	'extra' => sanitize_text_field( $_POST['pr-field'] )    
    	);
 		$cart_item_data['custom_data'] = $custom_data;
 	}

    
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'add_custom_data_to_cart_item', 10, 2);

// get cart item data...

function plugin_republic_get_item_data( $item_data, $cart_item_data ) {
 if( isset( $cart_item_data['custom_data'] ) ) {
    $item_data[] = array(
	   'key'   => __( 'Extra', 'plugin-republic' ),
	   'value' => $cart_item_data['custom_data']['extra'],
    );
 }
 return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'plugin_republic_get_item_data', 10, 2 );


function plugin_republic_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
 if( isset( $values['custom_data'] ) ) {
 $item->add_meta_data(
 __( 'Extra', 'plugin-republic' ),
 $values['custom_data']['extra'],
 true
 );
 }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'plugin_republic_checkout_create_order_line_item', 10, 4 );


function plugin_republic_order_item_name( $product_name, $item ) {
 if( isset( $item['pr_field'] ) ) {
 $product_name .= sprintf(
 '<ul><li>%s: %s</li></ul>',
 __( 'Your name', 'plugin_republic' ),
 esc_html( $item['pr_field'] )
 );
 }
 return $product_name;
}

add_filter( 'woocommerce_order_item_name', 'plugin_republic_order_item_name', 10, 2 );
