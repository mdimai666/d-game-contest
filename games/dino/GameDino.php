<?php
    
class GameDino {

    public $version = '0.5.0';//for cache reset

    public $name = '';
    
    public function __construct()
    {
        $this->name = 'dino';
    }

    public static function Dir(){
        return __DIR__.'/';
    }

    public function getDir(){
        return GameDino::Dir();
    }

    function getDWRelUrl(){
        $mm = plugins_url();
        $pp = 'wp-content/plugins';
        $pref = plugins_url("/dino/",dirname(__FILE__));
        $pref = $pp.str_replace($mm,'', $pref);
        // $pref = str_replace('/modules/kinokassa/','', $pref).'/saved/'.$this->name.'/';
        return '/'.$pref;
    }

    public function Index(){
        $v = "?v=".$this->version;
        $w = GameDino::Dir();
        $rel = $this->getDWRelUrl();
        $index = $w.'index.html';
        $html=[];

        if(!file_exists($index)) return "<h3>[файл $game не найден]</h3>";



        $jss = ['../gameclass.js', 'scripts.js', 'game.js'];
        foreach ($jss as $a) {
            $js = "<script src=\"$rel$a$v\"></script>";
            # code...
            $html[] = "$js";
        }

        $html[] = file_get_contents($index);


        // $html[] = "<script src=\"$rel../gameclass.js$v\"></script>";


        $html = implode('', $html);

        return $html;
    }

    public function GetTop(){

        $games = ['dino'];

        $tops = [];

        foreach ($games as $game_key) {

            $args = array(
                'posts_per_page' => 10,
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
                'post_type' => 'dgamescore',
        
                'orderby'			=> 'meta_value_num',
                'meta_key'			=> 'e_score',
                // 'meta_value'			=> 1,
                // 'order'				=> 'DESC',
                'order'				=> 'DESC',
                // 'orderby' => array( 'head_doctor__order_by' => 'ASC' ),
                // 'orderby' => array( 'head_doctor__order_by' => 'DESC' ),
                // 'orderby' => array( 'post_title__order_by' => 'ASC' ),
                // 'orderby' => array( 'post_title__order_by' => 'DESC' ),

                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key'     => 'e_game',
                        'value'   => $game_key,
                        'compare' => '='
                    ],
                ]
        
            );

            $query = new WP_Query($args);

            $tops[$game_key] = [
                'game' => $game_key,
                'posts' => $query->posts,
            ];

        }

        return $tops;
        
    }

    public function TopTable(){

        $top = $this->GetTop();

        ob_start();
        do_action('dgs_block_top_table', [
            'posts' => $top['dino']['posts'],
            'game' => 'dino',
        ] );
        return ob_get_clean();

    }
}