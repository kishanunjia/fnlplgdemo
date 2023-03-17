<?php 
global $wpdb;
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Expiring_Page_List_Table extends WP_List_Table {
    
    function get_data(){

        global $wpdb;
        $table_nm = $wpdb->prefix.'expiring_page_data';
        $results  = $wpdb->get_results("SELECT * FROM $table_nm Where 1 = 1 ",ARRAY_A);
        return $results;

    }

    //The code for expiring page listing columns
    function get_columns(){
        $columns = array(
            'cb'             => 'ID',
            'page_name'      => 'Page',
            'red_page_name'  => 'Redirect Page',
            'red_page_time'  => 'Redirect After',
            'status'         => 'Status',
            'remaining_time' => 'Remaining Time',
            'red_type'       => 'Redirect Type',
            'action'         => 'Action',
        );
        return $columns;
    }

    function column_red_page_name($item){
        $actions = array(
            'view' => sprintf('<a target="_blank" href="%s">%s</a>', get_the_permalink($item['red_page_id']), __('Page View', 'expiring-page')),
        );
        return sprintf('%s %s',
            $item['red_page_name'],
            $this->row_actions($actions)
        );
    }

    function column_page_name($item){
        $actions = array(
            'view' => sprintf('<a target="_blank" href="%s">%s</a>', get_the_permalink($item['page_id']), __('Page View', 'expiring-page')),
        );
        return sprintf('%s %s',
            $item['page_name'],
            $this->row_actions($actions)
        );
    }

    function column_red_type($item){
        return ($item['red_type'])?$item['red_type']:'';
    }

    function column_remaining_time($item){
        $red_page_time = $item['red_page_time'];
        $current_time = strtotime(wp_date('Y-m-d H:i:s'));

        $rtime =  $this->get_date_different($current_time,$red_page_time);
        return ($current_time > $red_page_time)?'-':$rtime;
    }

    // Find Remaining Time
    function get_date_different($date1,$date2){

        
          $diff = abs($date2 - $date1);
          $years = floor($diff / (365*60*60*24));
          $months = floor(($diff - $years * 365*60*60*24)
                                         / (30*60*60*24));
          $days = floor(($diff - $years * 365*60*60*24 -
                       $months*30*60*60*24)/ (60*60*24));
          $hours = floor(($diff - $years * 365*60*60*24
                 - $months*30*60*60*24 - $days*60*60*24)
                                             / (60*60));
          $minutes = floor(($diff - $years * 365*60*60*24
                   - $months*30*60*60*24 - $days*60*60*24
                                    - $hours*60*60)/ 60);
     
          $seconds = floor(($diff - $years * 365*60*60*24
                   - $months*30*60*60*24 - $days*60*60*24
                          - $hours*60*60 - $minutes*60));

          if($years != 0){
             return $years.' Year '. $months.' month';
          }

          if($months != 0){
             return $months.' month '. $days.' days';
          }

          if($days != 0){
             $dayslabel = ($days > 1)?' days ':' day ';
             $hourslabel = ($hours > 1)?' hours ':' hour ';
             $html = $days.$dayslabel.' ';
             if($hours != 0){
                $html.= $hours.$hourslabel.' ';
             }
             return $html;
          }

          if($hours != 0){
             $hourslabel = ($hours > 1)?' hours ':' hour ';
             $minutelabel = ($minutes > 1)?' mins ':' min ';
             $html = $hours.$hourslabel.' ';
             if($minutes != 0){
                 $html.= $minutes.$minutelabel.' ';
             }
             return $html;
          }

          if($minutes != 0){             
             $minutelabel = ($minutes > 1)?' mins ':' min ';
             $seclabel = ($seconds > 1)?' seconds ':' second ';             
             $html = $minutes.$minutelabel.' ';
             if($seconds != 0){
                 $html.= $seconds.$seclabel.' ';
             }
             return $html;
          }

          if($seconds != 0){
            $seclabel = ($seconds > 1)?' seconds ':' second ';
            return $seconds.$seclabel.' ';
          }

    }

    function column_status($item){
        $red_page_time = $item['red_page_time'];
        $current_time = strtotime(wp_date('Y-m-d H:i:s'));
        return ($current_time > $red_page_time)?'<span class="expired-label">Expired</span>':'<span class="active-label">Active</span>';
    }

    //The code for expiring page column action
    function column_action($item){
        $actions = array(
         'edit' => sprintf('<a href="?page=expiring_form&id=%s">%s</a>', $item['epd_id'], __('Edit', 'expiring-page')),
         'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['epd_id'], __('Delete', 'expiring-page')),
        );
        $html = sprintf('<a href="?page=expiring_form&id=%s">%s</a>', $item['epd_id'], __('Edit', 'expiring-page'));
        $html.= '&nbsp;&nbsp;&nbsp;'.sprintf('<a style="color:red;" href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['epd_id'], __('Delete', 'expiring-page'));
        return $html;
    }

    function column_cb($item){
        return sprintf('<input type="checkbox" name="id[]" value="%s" />',$item['epd_id']);
    }

    //The code for add bulk action
    
    
    function get_bulk_actions(){
        $actions = array('delete' => 'Delete');
        return $actions;
    }

   
    function process_bulk_action(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'expiring_page_data'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE epd_id IN($ids)");
            }
        }
    }
        

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'epd_id':
            case 'page_name':
            case 'red_page_name':
            case 'record_type':
              echo $item[ $column_name ];
            break;
            case 'page_id':
            case 'red_page_id':
              echo get_the_title($item[$column_name]);
            break; 
            case 'red_page_time':
              if(!empty($item[ $column_name ])){
                echo $red_page_time = date('m/d/Y H:i',$item[ $column_name ]);
              }
            break; 
            case 'action':
              echo '';
            break;                                
            default:
              echo '';
            break;
        }
    }                  

    
    
    function get_sortable_columns(){
        $sortable_columns = array(
            'page_name' => array('pm1.post_title', true),
            'red_page_name' => array('pm1.post_title', false),
            
        );
        return $sortable_columns;
    }
    
    

    function prepare_items(){

        global $wpdb;
        $table_name = $wpdb->prefix . 'expiring_page_data'; 
        $per_page = 5; // constant, how much records will be shown per page
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(p.epd_id) FROM $table_name p JOIN {$wpdb->prefix}posts pm1 ON p.page_id = pm1.ID 
        JOIN {$wpdb->prefix}posts pm2 ON p.red_page_id = pm2.ID ");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'p.epd_id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("
        SELECT p.*, pm1.post_title as page_name , pm2.post_title as red_page_name FROM $table_name p
        JOIN {$wpdb->prefix}posts pm1 ON p.page_id = pm1.ID 
        JOIN {$wpdb->prefix}posts pm2 ON p.red_page_id = pm2.ID
        ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        //$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }




}


global $wpdb;

$table = new Expiring_Page_List_Table();
$table->prepare_items();
$message = '';
if ('delete' === $table->current_action()) {
    
    if(is_array($_REQUEST['id'])){
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %s', 'expiring-page'), count($_REQUEST['id'])) . '</p></div>';
    }else{
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Item Successfully Deleted', 'expiring-page')) . '</p></div>';    
    }
    
}
?>
<div class="wrap">
    <div class="icon32 icon32-posts-post dashicons-before dashicons-admin-settings" id="icon-edit"><br></div>
    <h2>
        <?php _e('Expiring Page', 'expiring-page')?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=expiring_form');?>"><?php _e('Add new', 'expiring-page')?></a>
    </h2>
    <?php echo $message; ?>
    <form id="persons-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php



?>
