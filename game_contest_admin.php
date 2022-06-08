<?php
//Страница настройки социальных сетей




// if(__isAdmin())
add_action( 'admin_menu', 'd_game_contest_init' );



//https://stackoverflow.com/questions/21096539/creating-wordpress-admin-page-template-in-your-theme
function d_game_contest_init() {

    // add_menu_page( __('Кинотеатры'), __('Кинотеатры'), 
    //     'd_game_contest_page_cap', 'd_game_contest', 'd_game_contest_render', 'dashicons-share', 10 );

    
    // add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position)
    // add_submenu_page( 'myplugin/myplugin-admin-page.php', 'My Sub Level Menu Example', 'Sub Level Menu', 'manage_options', 'myplugin/myplugin-admin-sub-page.php', 'myplguin_admin_sub_page' ); 
    // add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );

    // add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function );

    add_submenu_page(
        'edit.php?post_type=dgamescore',
        __( 'Топ игрового рейтинга'),
        __( 'Топ'),
        'd_game_contest_page_cap',// 'manage_options',
        'd_game_contest__toppage',
        'd_game_contest_render',
        0
    );

}

function d_game_contest_render() {
    if ( !current_user_can( 'd_game_contest_page_cap' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    // $user = wp_get_current_user();

    $dino = new GameDino();
    $tops = $dino->GetTop();
    
    
    ?>
    <div class="wrap p12">

        <h1><?php _e('Топ игрового рейтинга') ?></h1>

        <div id="d_game_contest_page">
            

        <?php

            foreach ($tops as $game_key => $game):

                ?>

                <div class="game-header">
                    <h3><?=$game_key;?></h3>
                </div>

                    <!-- ================================= -->
                    <table class="wp-list-table widefat" style="">
                        
                    <tr>
                        <th style="padding:5px;">#</th>
                        <th style="padding:5px;">user</th>
                        <th style="padding:5px;">score</th>
                        <th style="padding:5px;">date</th>
                    </tr>

                    <?php

                        $n=1;
                        foreach ($game['posts'] as $p):

                            ?>

                            <tr>
                                <td><?=$n;?></td>
                                <td><?=$p->post_title;?></td>
                                <td><?=$p->e_score;?></td>
                                <td><?=$p->post_date;?></td>
                            </tr>                            
                            
                            <?php
                            $n++;
                        endforeach;

                    ?>

                    </table>
                    <!-- ================================= -->


                <?php

            endforeach;


        ?>

        <?php
            //  __dump( $tops );
        ?>

        </div> <!-- //#d_game_contest_page -->

    </div> <!-- //wrap -->

    <?php
}


/////////////////////////
function d_game_contest_notice__success($text = '') {
    ?>
    <div id="message-success" class="notice notice-success is-dismissible">
        <p>
            <?php echo $text ?>
        </p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Скрыть это уведомление.</span>
        </button>
    </div>
    <?php
}

function d_game_contest_notice__error($text = '') {
    ?>
    <div id="message-error" class="notice notice-error is-dismissible">
        <p>
            <?php echo $text ?>
        </p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Скрыть это уведомление.</span>
        </button>
    </div>
    <?php

}
/////////////////////////


//////////////////////////////////
//AJAX сохранение
if( wp_doing_ajax() ){
 
    // add_action('wp_ajax_d_game_contest_save_data', 'd_game_contest__save_data');

}


function d_game_contest__save_data() {
	
    check_admin_referer('myajax-nonce', 'nonce_code');

    try{
        $feed = new DSocialFeed();
        $option = $feed->get_option(true);

        $properties = ['url','token'];
        $feeds = $_POST['feeds'];


        foreach($feeds as $name => $val){
            foreach($properties as $prop){

                if(!isset($option['feeds'][$name]))
                    $option['feeds'][$name] = [];

                $option['feeds'][$name][$prop] = $val[$prop];
            }
        }

        foreach($option['feeds'] as $name => $val){

            $exist = array_key_exists($name, $feeds);

            if($exist == false) {
                unset($option['feeds'][$name]);
                Kinokassa::CleanDir($name);

            }
        }

        $option['tick'] = $option['tick'] + 1;

        $option['last_updated'] = (new DateTime(current_time( 'mysql')))->getTimestamp();

        $result = $feed->update_option($option);

        if(!$result)
            throw new Exception("Error Processing save option", 1);
        
        wp_send_json(array(
            'Result' => 'OK',
            'Message' => 'Сохранено',
        ));

    } catch (\Throwable $th) {
    // } catch (Exception $th) {
        wp_send_json(array(
            'Result' => 'error',
            'Message' => $th->getMessage(),
        ));
    }

}