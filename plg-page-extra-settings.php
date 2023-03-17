<?php 


function expiringp_settings_init() {
  // Register a new setting for "cxecf" page.
  register_setting( 'expiringp', 'expiringp_options' );

  // Register a new section in the "cxecf" page.
  add_settings_section(
    'expiringp_section_developers',
    __( 'Expiring Page Settings', 'expiring-page' ), 'expiringp_section_developers_callback',
    'expiringp'
  );

  //Timer Title
  add_settings_field(
    'countdown_timer_title_text',                  
    __( 'Timer Title ','expiring-page'),
    'countdown_timer_title_text_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_title_text',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer Title
  add_settings_field(
    'countdown_timer_bottom_text',                  
    __( 'Timer Bottom Text ','expiring-page'),
    'countdown_timer_bottom_text_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_bottom_text',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer background color
  add_settings_field(
    'countdown_timer_background_color',                  
    __( 'Timer Background Color ','expiring-page'),
    'countdown_timer_background_color_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_background_color',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer text color
  add_settings_field(
    'countdown_timer_text_color',                  
    __( 'Timer Text Color ','expiring-page'),
    'countdown_timer_text_color_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_text_color',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer text color
  add_settings_field(
    'countdown_timer_label_color',                  
    __( 'Timer Label Color (Days,Hours & Minute Label)','expiring-page'),
    'countdown_timer_label_color_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_label_color',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer Title
  add_settings_field(
    'countdown_time_end_text',                  
    __( 'Time End Message ','expiring-page'),
    'countdown_time_end_text_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_time_end_text',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer Title Font Size
  add_settings_field(
    'countdown_timer_title_size',                  
    __( 'Timer Title Font Size ','expiring-page'),
    'countdown_timer_title_size_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_title_size',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer Text Font Size
  add_settings_field(
    'countdown_timer_text_size',                  
    __( 'Timer Text Font Size ','expiring-page'),
    'countdown_timer_text_size_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_text_size',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer Label Font Size
  add_settings_field(
    'countdown_timer_label_size',                  
    __( 'Timer Label Font Size ','expiring-page'),
    'countdown_timer_label_size_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_label_size',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );

  //Timer Label Font Size
  add_settings_field(
    'countdown_timer_bottomtxt_size',                  
    __( 'Timer Bottom Text Font Size ','expiring-page'),
    'countdown_timer_bottomtxt_size_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_bottomtxt_size',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer Title Font Family
  add_settings_field(
    'countdown_timer_title_font',                  
    __( 'Timer Title Font Family ','expiring-page'),
    'countdown_timer_title_font_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_title_font_url',
      'label_font'        => 'countdown_timer_title_font',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer Text Font Family
  add_settings_field(
    'countdown_timer_text_font',                  
    __( 'Timer Text Font Family ','expiring-page'),
    'countdown_timer_title_font_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_text_font_url',
      'label_font'        => 'countdown_timer_text_font',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );


  //Timer Text Font Family
  add_settings_field(
    'countdown_timer_bottom_text_font',                  
    __( 'Timer Bottom Text Font Family ','expiring-page'),
    'countdown_timer_title_font_cb',
    'expiringp',
    'expiringp_section_developers',
    array(
      'label_for'         => 'countdown_timer_bottom_text_font_url',
      'label_font'        => 'countdown_timer_bottom_text_font',
      'class'             => 'expiringp_row',
      'expiringp_custom_data' => 'custom',
    )
  );



}
add_action( 'admin_init', 'expiringp_settings_init' );



function countdown_timer_title_font_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $default = '';
?>
  <input class="input-area-cls" placeholder="@import url('https://fonts.googleapis.com/css2?family=your-font');" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $default ); ?>">

  <input class="input-area-cls" placeholder="Font Family Name Add Here" id="<?php echo esc_attr( $args['label_font'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_font'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_font'] ] ) ? ($options[$args['label_font']]) : $default ); ?>">
<?php
}


function expiringp_section_developers_callback( $args ) {
  ?>
  <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Add the below shortcode for the countdown timer.', 'expiring-page' ); ?></p>
  <p id="new<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Shortcode :', 'expiring-page' ); ?> [expiringp_countdown_timer]</p>
  <?php
}

function countdown_timer_bottomtxt_size_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '20','expiring-page');
?>
  <input class="input-area-cls-num" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="number" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" min="5" max="60" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}

function countdown_timer_label_size_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '14','expiring-page');
?>
  <input class="input-area-cls-num" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="number" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" min="5" max="50" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}


function countdown_timer_text_size_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '55','expiring-page');
?>
  <input class="input-area-cls-num" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="number" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" min="12" max="90" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}

function countdown_timer_title_size_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '33','expiring-page');
?>
  <input class="input-area-cls-num" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="number" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" min="12" max="70" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}


function countdown_time_end_text_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( 'Time is ended','expiring-page');
?>
  <input class="input-area-cls" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}


function countdown_timer_position_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $val = esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : 'top');
?>
  <select name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <option <?php echo ($val == 'top')?'selected':''; ?> value="top">Before Content</option>
    <option <?php echo ($val == 'bottom')?'selected':''; ?> value="bottom">After Content</option>
  </select>  
<?php
}

function countdown_timer_label_color_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '#666666','expiring-page');
?>
  <input class="input-area-cls my-color-field" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}

function countdown_timer_background_color_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '#e2e2e2','expiring-page');
?>
  <input class="input-area-cls my-color-field" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}


function countdown_timer_text_color_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( '#000000','expiring-page');
?>
  <input class="input-area-cls my-color-field" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}

function countdown_timer_bottom_text_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( 'you will love it','expiring-page');
?>
  <input class="input-area-cls" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}

function countdown_timer_title_text_cb( $args ) {
  // Get the value of the setting we've registered with register_setting()
  $options = get_option( 'expiringp_options' );
  $countdown_timer_title_text = __( 'Offer Is Valid Until','expiring-page');
?>
  <input class="input-area-cls" id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="expiringp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_html(  isset( $options[ $args['label_for'] ] ) ? ($options[$args['label_for']]) : $countdown_timer_title_text ); ?>" required>
<?php
}




