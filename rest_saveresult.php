<?php
    
if( wp_doing_ajax() ){
 
    add_action('wp_ajax_d_game_contest_saveresult', 'd_game_contest__saveresult');

}

function count_digit($number) {
    return strlen($number);
  }

function dgc_checksum($score){
    // $date = new DateTime();
    // return intval($date->getTimestamp() / 86400);

    // return count_digit($score);
    return intval($score*$score*$score/count_digit($score)-2222);
}

function d_game_contest__saveresult() {
 
    check_admin_referer('myajax-nonce', 'nonce_code');

    $score = intval($_POST['score']);
    $post_game = $_POST['g'];
    $post_csi = $_POST['cs'];

    $csi = dgc_checksum($score);

    if($score<1 || $csi != $post_csi){
        return wp_send_json(array(
            'Result' => 'OK',
            'Message' => 'fc',
            'score' => $score,
            // 't' => dgc_checksum($score),
        ),400);
    }


    try{
        $games = [
            5975 => 'dino'
        ];

        $e_game = $games[$post_game];

        $user = wp_get_current_user()->data->user_login;
        
        // return wp_send_json(array(
        //     'Result' => 'OK',
        //     'Message' => 'fc',
        //     // 'd' => $user
        // ),200);
        
        
        $post = [
            'post_title' => $user,
            'post_type' => 'dgamescore',
            'post_status' => 'publish',
            'meta_input' => [
                'e_game' => $e_game,
                'e_score' => $score,
                
            ],
        ];

        $result = wp_insert_post($post);

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