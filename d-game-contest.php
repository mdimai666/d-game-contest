<?php
/*
Plugin Name: D Game Contest
Plugin URI: http://amai-lab.com
Description: Игровые конкурсы
Version: 0.5
Author: mdimai666
Author URI: http://amai-lab.com
*/

require_once 'post_dgamescore.php';
require_once 'game_contest_admin.php';
require_once 'shortcodes.php';
require_once 'rest_saveresult.php';
require_once 'games/dino/GameDino.php';
require_once 'dgs_block_top_table.php';


$scripts_version = '0.5.0'; //for cache update



// for front end
// add_action('wp_enqueue_scripts', array(&$this, 'dgame_contest_enqueue_scripts'));
// for back end
// add_action('admin_enqueue_scripts', array(&$this, 'dgame_contest_enqueue_scripts'));
add_action('admin_enqueue_scripts', 'dgame_contest_enqueue_admin_scripts');

function dgame_contest_enqueue_admin_scripts() {
    if ( 1 ) {
        wp_register_script('dparser_adminscripts', plugins_url('/front/adminscripts.js',__FILE__),'', $scripts_version,true);
        wp_enqueue_script('dparser_adminscripts');

    }
}


add_action('wp_enqueue_scripts', 'dgame_contest_enqueue_scripts');

function dgame_contest_enqueue_scripts() {
    if ( 1 ) {
        wp_register_script('dgc_app_hooks', plugins_url('/front/app_hooks.js',__FILE__),'', $scripts_version,true);
        wp_enqueue_script('dgc_app_hooks');

    }
}

function d_game_contest_varibles_admin_js() {
    
    $amai = json_encode([
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('myajax-nonce'),
    ]);

    echo "
        <script>
            var d_game_contest_varibles = $amai;
        </script>";
}
add_action('admin_footer', 'd_game_contest_varibles_admin_js');
add_action('wp_footer', 'd_game_contest_varibles_admin_js');

//--------------------------------------------------------------------
register_activation_hook( __FILE__, 'd_game_contest__activate' );

function d_game_contest__activate(){
    $role = get_role('administrator');
    $role->add_cap("d_game_contest_page_cap", true);
}
//--------------------------------------------------------------------

// http://wordpress.localhost:81/wp-json/dgc/v1/status
add_action( 'rest_api_init', function () {
    // register_rest_route( 'dsearch/v1', '/posts/(?P<id>\d+)', array(
    //   'methods' => 'GET',
    //   'callback' => 'd_game_contest__ajax_status',
    // ));
    register_rest_route( 'dgc/v1', '/status/', array(
      'methods' => ['GET'],
      'callback' => 'd_game_contest__ajax_status',
    ));
    register_rest_route( 'dgc/v1', '/top_table/', array(
      'methods' => ['GET'],
      'callback' => 'd_game_contest__ajax__top_table',
    ));
});

if ( ! function_exists( 'd_game_contest__ajax_status' ) ) :
function d_game_contest__ajax_status(){

    $response = [
        'Response' => 'OK',
    ];

    wp_send_json($response);
    // wp_send_json($response);

}
endif;



//--------------------------------------------------------------------
function d_game_contest__ajax__top_table(){

    $game = $_GET['game'];

    // ob_start();

    // wp_send_json($response);
    // wp_send_json($response);
    header('Content-Type: text/html');

    echo do_shortcode("[gamecontest_table game=$game]");

    // echo 123;

    // return ob_get_clean();

}
//--------------------------------------------------------------------

if ( ! function_exists( '__dump' ) ) {
    function __dump($var){
        highlight_string("<?php\n" . var_export($var, true) . ";\n?>");
    }
}