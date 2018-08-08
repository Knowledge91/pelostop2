<?php
/**
 * The7 theme.
 * @package The7
 * @since   1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set the content width based on the theme's design and stylesheet.
 * @since 1.0.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200; /* pixels */
}

/**
 * Initialize theme.
 * @since 1.0.0
 */
require( trailingslashit( get_template_directory() ) . 'inc/init.php' );

// add bootstrap
function my_scripts() {
  wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css');
  wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
}
add_action( 'wp_enqueue_scripts', 'my_scripts' );

/**
 * Add a custom product tab.
 */
function custom_product_tabs( $tabs) {
	$tabs['centers'] = array(
		'label'		=> __( 'Centers', 'woocommerce' ),
		'target'	=> 'centers_options',
		//'class'		=> array( 'show_if_simple', 'show_if_variable'  ),
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );
/**
 * Contents of the gift card options product tab.
 */
function giftcard_options_product_tab_content() {
	global $post;
    $centers = get_posts(array('post_type' => 'center'));

	// Note the 'id' attribute needs to match the 'target' parameter set above
	?><div id='centers_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php
              foreach ($centers as $center) {
                $id = $center->ID;
                $title = $center->post_title;
                woocommerce_wp_checkbox( array(
                    'id' 		=> "center_$id",
                    'label' 	=> __( $title, 'woocommerce' ),
                                               ) );
              }
		?></div>

	</div><?php
}
add_filter( 'woocommerce_product_data_panels', 'giftcard_options_product_tab_content' ); // WC 2.6 and up

/**
 * Save the custom fields.
 */
function save_giftcard_option_fields( $post_id ) {
  $centers = get_posts(array('post_type' => 'center'));
  foreach($centers as $center) {
    $center_key = "center_" . $center->ID;
	$has_center = isset( $_POST[$center_key] ) ? 'yes' : 'no';
    echo $center->ID . " " . $has_center;
	update_post_meta($post_id, $center_key, $has_center);
  }
}
add_action( 'woocommerce_process_product_meta_simple', 'save_giftcard_option_fields'  );
add_action( 'woocommerce_process_product_meta_variable', 'save_giftcard_option_fields'  );


/**
   Center Post Type
 */
// var_dump(get_posts(array('post_type' => 'center')));


function create_post_type() {
  register_post_type( 'center',
                      array(
                          'labels' => array(
                              'name' => __( 'Centers' ),
                              'singular_name' => __( 'Center' )
                                            ),
                          'public' => true,
                          'has_archive' => true,
                          'supports' => array('title')
                            )
                      );
}
add_action( 'init', 'create_post_type' );

$arr_centros = array(
	array('id'=>'centro_id','nombre'=>'Centro ID'),
	array('id'=>'nombre_web','nombre'=>'Nombre Web'),
	array('id'=>'calle','nombre'=>'Calle'),
	array('id'=>'numero','nombre'=>'Número'),
	array('id'=>'puerta','nombre'=>'Puerta'),
	array('id'=>'cp','nombre'=>'Código Postal'),
	array('id'=>'poblacion','nombre'=>'Población'),
	array('id'=>'provincia','nombre'=>'Provincia'),
	array('id'=>'telefono','nombre'=>'Teléfono'),
	array('id'=>'email','nombre'=>'Email'),
	array('id'=>'horarios','nombre'=>'Horarios'),
	array('id'=>'latitud','nombre'=>'Latitud'),
	array('id'=>'longitud','nombre'=>'Longitud'),
	array('id'=>'empresa','nombre'=>'Grupo/Empresa'),
	array('id'=>'venta_paypal','nombre'=>'Paypal Disponible'),
	array('id'=>'venta_addons','nombre'=>'Addons Disponible'),
	array('id'=>'venta_redsys','nombre'=>'Redsys Disponible'),
                     );
function centros_register_meta_fields() {
  global $arr_centros;
  foreach($arr_centros as $centro){
    register_meta('post',$centro['id'],'sanitize_text_field');
  }
}
add_action('init', 'centros_register_meta_fields');

function centers_meta_boxes() {
  add_meta_box('centers-meta-box', 'Datos del Centro', 'centers_meta_box_callback', 'center', 'normal','high');
}
add_action('add_meta_boxes', 'centers_meta_boxes' );

function centers_meta_box_callback($post){
  global $wpdb, $post, $arr_centros;
  foreach($arr_centros as $centro){
    print '<p><label class="label">'.$centro['nombre'].'</label><br/>';
    print '<input name="'.$centro['id'].'" id="'.$centro['id'].'" type="text" value="'.htmlspecialchars(get_post_meta($post->ID, $centro['id'], true)).'"></p>';
  }
}

function save_center() {
  global $wpdb, $post, $arr_centros;
  $post_id = $_POST['post_ID'];
  if (!$post_id) return $post;


  foreach($arr_centros as $centro){
    update_post_meta($post_id, $centro['id'], $_REQUEST[$centro['id']]);
  }
}
add_action('save_post', 'save_center');
add_action('publish_post', 'save_center');

// single product page
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

function my_custom_action() {
  ?>
  <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
      </button>

  <!-- Modal -->
       <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
  ...
             </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary">Save changes</button>
  </div>
  </div>
  </div>
  </div>
  <button>test</button>
  <?php
};
add_action( 'woocommerce_single_product_summary', 'my_custom_action', 30 );


// Helpers
function dump($var) {

  echo "<div><pre>";
  var_dump($var);
  echo "</pre></div>";
}