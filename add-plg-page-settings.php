<?php 
    global $wpdb;
    $table_name = $wpdb->prefix . 'expiring_page_data';

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'epd_id'        => 0,
        'page_id'       => 0,
        'page_type'     => 'page',
        'red_page_id'   => 0,
        'red_page_time' => '',
        'red_type'      => '',
    );

    // here we are verifying does this request is post back and have correct nonce
    if(isset($_REQUEST['expiringpg_nonce'])){

        if (wp_verify_nonce($_REQUEST['expiringpg_nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            $item_valid = expiringpage_validate_data($item);
            if ($item_valid === true) {
                
                $item['red_page_time'] = strtotime( $item['red_page_time'] );
                if ($item['epd_id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['epd_id'] = $wpdb->insert_id;
                    if ($result) {
                        $message = __('Item was successfully saved', 'expiring-page');
                    } else {
                        $notice = __('There was an error while saving item', 'expiring-page');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('epd_id' => $item['epd_id']));
                    if ($result) {
                        $message = __('Item was successfully updated', 'expiring-page');
                    } else {
                        $notice = __('There was an error while updating item', 'expiring-page');
                    }
                }
            } else {
                $notice = $item_valid;
            }
        }
        else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            if (isset($_REQUEST['id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE epd_id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', 'expiring-page');
                }
            }
        }

    }else{
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            if (isset($_REQUEST['id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE epd_id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', 'expiring-page');
                }
            }        
    }
    // here we adding our custom meta box
    add_meta_box('expiring_form_meta_box', 'Expiring Page Data', 'expiringpage_persons_form_meta_box_handler', 'expiring_page', 'normal', 'default');

    ?>
<div class="wrap add-expiring-main">
    
    <h2 class="expiring-add-title"><span class="dashicons <?php echo ($item['epd_id'] == 0)?'dashicons-welcome-add-page':'dashicons-welcome-write-blog'; ?>" id="icon-edit"></span> <?php ($item['epd_id'] == 0)?_e('Add ', 'expiring-page'):_e('Edit ', 'expiring-page'); _e('Expiring Page', 'expiring-page'); ?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=expiring-page');?>"><?php _e('Back To List', 'expiring-page')?></a>
    </h2>
    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="expiringpg_nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <input type="hidden" name="epd_id" value="<?php echo $item['epd_id'] ?>"/>
        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('expiring_page', 'normal', $item); ?>
                    <input type="submit" value="<?php ($item['epd_id'] == 0)?_e('Save', 'expiring-page'):_e('Update', 'expiring-page'); ?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php

function expiringpage_validate_data($item){

    global $wpdb;
    $table_name = $wpdb->prefix . 'expiring_page_data';
    $messages = array();
    if (empty($item['page_id']) && $item['page_id'] == 0) $messages[] = __('Page is required.', 'expiring-page');
    if (empty($item['red_page_id']) && $item['red_page_id'] == 0) $messages[] = __('Redirect page is required.', 'expiring-page');
    if (empty($item['red_page_time'])) $messages[] = __('Redirect Date & Time is required.', 'expiring-page');
    
    if(empty($messages)){
        if($item['page_id'] == $item['red_page_id']){
            $messages[] = __('Please select different page for page & redirect page.', 'expiring-page');
        }
    }
    if(empty($messages)){
       
        $final_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name Where (page_id IN (%d,%d) OR red_page_id IN (%d,%d)) AND epd_id <> %d", $item['page_id'],$item['red_page_id'], $item['page_id'],$item['red_page_id'],$item['epd_id']), ARRAY_A);
        if(!empty($final_data)){
            $messages[] = __('Page or Redirect Page is already used for redirect.', 'expiring-page');
        }
    }
    if (empty($messages)) return true;
    return implode('<br />', $messages);

}


/* This function renders our custom meta box */
function expiringpage_persons_form_meta_box_handler($item){

    $all_pages = get_posts(array('showposts'=>-1,'post_type'=>'page'));

?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="name"><?php _e('Page', 'expiring-page')?></label>
        </th>
        <td>
            <?php  
                 $page_id = (isset($item['page_id']))?esc_attr($item['page_id']):'';
            ?>
            <select id="page_id" name="page_id" class="expiring-frm-input">
                <option value="">Select Page</option>
                <?php 
                    foreach($all_pages as $page){
                ?>
                <option <?=($page_id == $page->ID)?'selected':'';?> value="<?=$page->ID;?>"><?=$page->post_title;?></option>
                <?php 
                    }
                ?>
            </select>    
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email"><?php _e('Redirect Page', 'expiring-page')?></label>
        </th>
        <td>
            <?php  
                 $red_page_id = (isset($item['red_page_id']))?esc_attr($item['red_page_id']):'';
            ?>
            <select id="red_page_id" name="red_page_id" class="expiring-frm-input">
                <option value="">Select Page</option>
                <?php 
                    foreach($all_pages as $page){
                ?>
                <option <?=($red_page_id == $page->ID)?'selected':'';?> value="<?=$page->ID;?>"><?=$page->post_title;?></option>
                <?php 
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <?php  
           $red_page_time = (isset($item['red_page_time']))?esc_attr($item['red_page_time']):'';
           if(!empty($red_page_time)){
              $red_page_time = date('m/d/Y H:i',$red_page_time);
           }
        ?>        
        <th valign="top" scope="row">
            <label for="age"><?php _e('Redirect Date & Time', 'expiring-page')?></label>
        </th>
        <td>
            <input id="red_page_time" autocomplete="off" name="red_page_time" type="text" class="datetimerange expiring-frm-input" value="<?php echo  $red_page_time; ?>"
                    size="50" class="code" placeholder="<?php _e('Redirect Date & Time', 'expiring-page')?>">
            <input type="hidden" name="page_type" value="page">        
        </td>
    </tr>
    <tr class="form-field">
        <?php  
           $red_type = (isset($item['red_type']))?esc_attr($item['red_type']):'';
        ?>        
        <th valign="top" scope="row">
            <label for="age"><?php _e('Redirect Type', 'expiring-page')?></label>
        </th>
        <td>
            <select id="red_type" name="red_type" class="expiring-frm-input">
                <option <?php echo ($red_type == 302)?'selected':''; ?> value="302">302: Found But Moved Temporarily</option>
                <option <?php echo ($red_type == 303)?'selected':''; ?> value="303">303: See Other</option>
                <option <?php echo ($red_type == 301)?'selected':''; ?> value="301">301: Moved Permanently</option>
                <option <?php echo ($red_type == 307)?'selected':''; ?> value="307">307: Temporarily Redirected</option>
                <option <?php echo ($red_type == 410)?'selected':''; ?> value="410">410: Content Delete</option>
                <option <?php echo ($red_type == 451)?'selected':''; ?> value="451">451: Unavailable For Legal Reasons</option>
            </select>                   
        </td>

    </tr>

    </tbody>
</table>
<?php
}