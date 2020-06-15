<?php
/**
 * %THEME_NAME% Theme Widgets.
 *
 * @package %DOMAIN_NAME%
 */

class GWP_Contacts_Widget extends WP_Widget {
  public function __construct() {
    parent::__construct(
      'gwp_contacts',
      'GWP Contatti',
      array(
        'description' => __('Aggiungi i contatti della tua attività.', '%DOMAIN_NAME%')
      )
    );
  }
  public function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if ( !empty($title) ) {
      echo $before_title . $title . $after_title;
      unset($instance['title']);
    }
    echo '<address>';
    foreach($instance as $key => $value) {
      if ($value !== '') {
        $output_value = preg_replace('/\n/', '<br />', $value);
        if ($key === 'phone') {
          preg_match_all('/([0-9]+)/', $output_value, $matches);
          foreach($matches[0] as $num) {
            $output_value = str_replace($num, '<a href="tel:'.$num.'">'.$num.'</a>', $output_value);
          }
          echo '<span>'.$output_value.'</span>';
        } else if ($key === 'email') {
          echo '<a href="mailto:'.$output_value.'">'.$output_value.'</a>';
        } else {
          echo '<p>'.$output_value.'</p>';
        }
      }
    }
    echo '</address>';
    echo $after_widget;
  }
  public function form($instance) {
    $title = isset($instance['title']) ? $instance['title'] : '';
    $description = isset($instance['description']) ? $instance['description'] : '';
    $email = isset($instance['email']) ? $instance['email'] : '';
    $phone = isset($instance['phone']) ? $instance['phone'] : '';
    $address = isset($instance['address']) ? $instance['address'] : '';
  ?>
  <p>
    <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_name('description'); ?>"><?php _e('Descrizione:', '%DOMAIN_NAME%'); ?></label>
    <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="4"><?php echo esc_attr($description); ?></textarea>
  </p>
  <p>
    <label for="<?php echo $this->get_field_name('phone'); ?>"><?php _e('Telefono:', '%DOMAIN_NAME%'); ?></label>
    <textarea class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" rows="2"><?php echo esc_attr($phone); ?></textarea>
  </p>
  <p>
    <label for="<?php echo $this->get_field_name('email'); ?>"><?php _e('E-mail:', '%DOMAIN_NAME%'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="email" value="<?php echo esc_attr($email); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_name('address'); ?>"><?php _e('Indirizzo:', '%DOMAIN_NAME%'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo esc_attr($address); ?>" />
  </p>
  <?php
  }
  public function update($new_instance, $old_instance) {
    $instance = array();
    foreach($new_instance as $key => $value) {
      $instance[$key] = !empty($new_instance[$key]) ? strip_tags($value) : '';
    }
    return $instance;
  }
}

class GWP_ContactForm_Widget extends WP_Widget {
  public function __construct() {
    parent::__construct(
      'gwp_cf7',
      'GWP Form contatti',
      array(
        'description' => __('Aggiungi un modulo di contatto CF7.', '%DOMAIN_NAME%')
      )
    );
  }
  public function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if ( !empty($title) ) {
      echo $before_title . $title . $after_title;
      unset($instance['title']);
    }
    if ( !empty($instance['form_id']) ) {
      echo do_shortcode('[contact-form-7 id="'.$instance['form_id'].'"]');
    }
    echo $after_widget;
  }
  public function form($instance) {
    $title = isset($instance['title']) ? $instance['title'] : '';
    $form_id = isset($instance['form_id']) ? $instance['form_id'] : false;
    $cf7_args = array('post_type' => 'wpcf7_contact_form', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'name', 'order' => 'ASC');
  ?>
    <p>
      <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_name('form_id'); ?>"><?php _e('Seleziona modulo:', '%DOMAIN_NAME%'); ?></label>
      <select class="widefat" id="<?php echo $this->get_field_id('form_id'); ?>" name="<?php echo $this->get_field_name('form_id'); ?>">
        <option value=""<?php if(!$form_id){echo ' selected';} ?>>--</option>
        <?php if( $cf7_forms = get_posts($cf7_args) ): foreach($cf7_forms as $form): ?>
          <option value="<?php echo esc_attr($form->ID); ?>"<?php if($form->ID == $form_id){echo ' selected';} ?>><?php echo esc_html($form->post_title); ?></option>
        <?php endforeach; endif; ?>
      </select>
    </p>
  <?php
  }
  public function update($new_instance, $old_instance) {
    $instance = array();
    foreach($new_instance as $key => $value) {
      $instance[$key] = !empty($new_instance[$key]) ? strip_tags($value) : NULL;
    }
    return $instance;
  }
}

function %DOMAIN_NAME%_register_widgets() {
  if ( class_exists('GWP_Contacts_Widget') ) {
    register_widget('GWP_Contacts_Widget');
  }
  if ( class_exists('GWP_ContactForm_Widget') && class_exists('WPCF7') ) {
    register_widget('GWP_ContactForm_Widget');
  }
}
add_action('widgets_init', '%DOMAIN_NAME%_register_widgets');
