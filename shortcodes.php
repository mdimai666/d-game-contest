<?php


add_shortcode( 'gamecontest', 'dsh_gameframe' );

function dsh_gameframe( $atts, $content, $tag ) {

    // какая-то общая логика, например обработка переменных $atts
    $atts = shortcode_atts( [
        'game' => '0',
    ], $atts );

    if($atts['game'] == '0') return "<h3>[Требуется game]</h3>";

    $game = $atts['game'];

    if($game == 'dino'){
        
        $dino = new GameDino();
        $html = $dino->Index();

        return $html;

    } else {
        return "<h3>[игра $game не найдена]</h3>"; 
    }

    // $html = file_get_contents(__DIR__.'/test_index.html');
    // $html = file_get_contents(__DIR__.'/modules/kinokassa/dw/index.html');
    // $content = $html;

    // $content = 'Конетнт шорткода '.$atts['id'];

    return $content;
}