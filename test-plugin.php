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


//Ajax Function Here....

add_action('wp_footer', 'eps_footer');

function eps_footer() {
    
    echo "<script>var ajax_request_url = '".admin_url( 'admin-ajax.php' )."'</script>";

}


add_action( 'wp_ajax_nopriv_favourite_unfavourite_call', 'favourite_unfavourite_call_function' );
add_action('wp_ajax_favourite_unfavourite_call', 'favourite_unfavourite_call_function');
function favourite_unfavourite_call_function(){
    global $wpdb;
    $reponse = array();
    $res='Yes';
    $user_id=$_POST['user_id'];
    $fav_user_id=$_POST['fav_user_id'];
    $fav_post_id=$_POST['fav_post_id'];
    $fav_user_type=$_POST['fav_user_type'];
    
    
    if(!empty($user_id) && !empty($fav_post_id))
    {

     $table_name = $wpdb->prefix.'favorite_list';
      $list = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = ".$user_id." AND fav_post_id=".$fav_post_id."");
      if(empty($list))
      {
        $wpdb->insert( $wpdb->prefix . 'favorite_list', 
            array( 
                'user_id'          => $user_id,
                'fav_user_id'      => $fav_user_id,
                'fav_post_id'      => $fav_post_id,             
                'fav_user_type'    => $fav_user_type,               
            )
        );
        
        $lastid = $wpdb->insert_id;   
        if($lastid)
        {           
          $response['response'] = add_successfully_favourite;
          $response['msg'] = Remove_to_Favorite;
          $response['action'] = 'add';
        }
      }
      else
      {
        $wpdb->delete( $table_name, array( 'user_id' => $user_id,'fav_post_id' => $fav_post_id ));                      
        $response['response'] = remove_successfully_favourite;
        $response['msg'] = Add_to_Favorite;
        $response['action'] = 'del';          
      }
    }
    else
    {
      $response['response'] = "N";
      $response['msg'] = report_claim_user_not_found_msg;   
    }
    
    

    
    header( "Content-Type: application/json" );
    echo json_encode($response);    
    exit();
}

function club_blog_pagination_function(){
    global $wpdb;

    $reponse = array();
    if(!empty($_POST['page_number']) && !empty($_POST['logic_id'])){        
    $res='';
    $pagination='';  
    $page_number = $_POST['page_number'];
    $logic_id = $_POST['logic_id'];
    //$res.=$page_number;       
    wp_reset_query();
    global $wp_query; 
         
   $paged = $page_number;
   $args = array(    
        'orderby'       =>  'date',
        'order'         =>  'DESC',
        //'posts_per_page' => 2,
        'post_type'     => 'blogs',
        'meta_query'    => array( array( 'key' => 'link_club', 'value' => $logic_id)),
        'paged'         => $paged
    );
    $wp_query = new WP_Query($args);
    //query_posts($args);
    if($wp_query->have_posts())
    {     
        while($wp_query->have_posts()){  
         $wp_query->the_post();
         $id=get_the_ID();
         $img_src = get_template_directory_uri().'/images/no-image.png';
         $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'main-size' );
         if(!empty($image_attributes))
         {
            $img_src = $image_attributes[0];
         }
         
          $res.='<div class="blog-list-main">';
          
          $res.='<div class="blogimg"><div class="blog-img-section">';
          $res.='<a class="open-popup-link" href="#blog-popup-'.get_the_ID().'"><img src="'.$img_src.'" alt="'.get_the_title().'" /></a>';
          $res.='</div></div>';
          
          $res.='<div class="blog-inner-content">';
          $res.='<h3><a class="open-popup-link" href="#blog-popup-'.get_the_ID().'">'.get_the_title().'</a></h3>';
          $res.='<div class="post-date"><i class="fa fa-calendar"></i>'.get_the_time("j F, Y").'</div>';
          $res.='<div class="blog-content"><p>'.wp_trim_words( get_the_content(), 60, "..." ).'</p></div>';
          $res.='<a class="readmorelink open-popup-link" href="#blog-popup-'.get_the_ID().'">'.Club_Blog_Read_more_button_Label.'</a>';
          $res.='</div>';                         
          $res.='</div>';
         
         $post = get_post(get_the_ID()); 
         $content = $post->post_content;
         $content = apply_filters('the_content', $content); 
         $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' ); 
         if(!empty($image_attributes))
         {
            $img_src = $image_attributes[0];
         }        
        $res.='<div id="blog-popup-'.get_the_ID().'" class="white-popup mfp-hide">
        <div class="gc-club-blog-popup">';
          if(!empty($image_attributes)){
          $res.='<div class="blogimg">
            <div class="blog-img-section">
              <img src="'.$img_src.'" alt="'.get_the_title().'">
            </div>
          </div>';
          }
          $res.='<div class="blog-inner-content">
            <h3>'.get_the_title().'</h3>
            <div class="post-date"><i class="fa fa-calendar"></i>'.get_the_time('j F, Y').'</div>
            <div class="blog-content">'.$content.'</div>
          </div>                                    
        </div>
        </div>';         
          
         
         
        }
        
        $res.='<div class="pagination pagination-div">';
        
$res.='<div class="pagination-loading"><img src="'.get_template_directory_uri().'/images/loading.gif" alt="Loading image" /></div>';                                               
        $big = 999999999; // need an unlikely integer       
        $res.=paginate_links( array(
                                            //'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                            'base' => 'javascript:void(0);',
                                            'format' => '?paged=%#%',
                                            'current' => max( 1, $page_number),
                                            'total' => $wp_query->max_num_pages
                                        ) );
                                        
        $res.='</div>';
        
        
        
    }

$res.="
<script>
jQuery(document).ready(function(e) {
    jQuery('.page-numbers').click(function(e){
        e.preventDefault();
        if(jQuery(this).hasClass('next'))
        {
            var page_number = parseInt(jQuery('.pagination-div .current').html())+1;
        }
        else if(jQuery(this).hasClass('prev'))
        {
            var page_number = parseInt(jQuery('.pagination-div .current').html())-1;
        }       
        else
        {
          var page_number = jQuery(this).html();
        }
        var logic_id = ".$logic_id.";
        
        jQuery('.pagination-loading').show();       
        jQuery.ajax({
            type: 'POST',
            url: ajax_request_url,
            data: { action: 'club_blog_pagination' , page_number: page_number,logic_id:logic_id}
          }).done(function(msg){                     
                 if(msg.response != 'N')
                 {
                   
                   jQuery('.club-blog-ajax-call').html(msg.response);
                 }
                 else
                 {
                   alert('Problem in Loading Blogs');    
                 }
          });       
        
    });    
});
jQuery('a.open-popup-link').magnificPopup({
  mainClass: 'mfp-with-fade',
  removalDelay: 500,
  callbacks: {
    beforeClose: function() {
        this.content.addClass('hinge');
    }, 
    close: function() {
        this.content.removeClass('hinge'); 
    }
  },
  midClick: true
});
</script>
";           
    wp_reset_query();    
         
         $response['response'] = $res;
    } else {
         $response['response'] = "N";
    }

    header( "Content-Type: application/json" );
    echo json_encode($response);

    //Don't forget to always exit in the ajax function.
    exit();

}



?>

<script>
    jQuery('.removefav').click(function(){
        var user_id     = jQuery(this).attr('user_id');
        var fav_post_id = jQuery(this).attr('fav_post_id');
        if(confirm("<?php echo remove_favourite_sure_msg; ?>"))
        {
        jQuery.ajax({
            type: "POST",
            url: ajax_request_url,
            data: { action: 'favourite_unfavourite_call' , user_id: user_id,'fav_user_id':'',fav_post_id:fav_post_id,'fav_user_type':''}
        }).done(function(res){                   
             if(res.response != 'N')
             {                         
                if(res.action != 'add'){                  
                  //window.location.href=window.location.href;  
                  jQuery('.gc-fav'+fav_post_id).remove();
                }                              
             }
             else
             {         
               alert(res.msg);
             }
                             
        });
                
        }
        
    });


jQuery(document).ready(function(e) {
    jQuery('.page-numbers').click(function(e){
        e.preventDefault();
        
        if(jQuery(this).hasClass('next'))
        {
            var page_number = parseInt(jQuery('.pagination-div .current').html())+1;
        }
        else if(jQuery(this).hasClass('prev'))
        {
            var page_number = parseInt(jQuery('.pagination-div .current').html())-1;
        }       
        else
        {
          var page_number = jQuery(this).html();
        }
        if(!jQuery(this).hasClass('current')){
            var logic_id = <?php echo get_the_ID(); ?>;     
            jQuery('.pagination-loading').show();
            jQuery.ajax({
                type: "POST",
                url: ajax_request_url,
                data: { action: 'club_blog_pagination' , page_number: page_number,logic_id:logic_id}
              }).done(function(msg){                     
                     if(msg.response != 'N')
                     {                 
                       jQuery('.club-blog-ajax-call').html(msg.response);
                     }
                     else
                     {
                       alert('Problem in Loading Blogs');    
                     }
              });       
        }
    });    
});


</script>    



$products = new WP_Query( array( 
  'post_type' => 'products',
  'posts_per_page' => 15,
  'orderby' => 'title',
  'order'   => 'ASC',
  'paged' => $paged,
  'tax_query' => array(
    'relation' => 'OR',
     array(
       'taxonomy' => 'producttype',
       'field' => 'name',
       'terms' => $producttype
     ),
     array(
       'taxonomy' => 'businessunit',
       'field' => 'name',
       'terms' => $businessunit
     )
);
