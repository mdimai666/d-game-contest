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

        if(!file_exists($index)) return "<h3>[файл $game не найден]</h3>";

        $html = file_get_contents($index);

        $js = $rel.'scripts.js';
        $js = "<script src=\"$js$v\"></script>";

        $html = "$js".$html;
        $html = "<script src=\"$rel../gameclass.js$v\"></script>".$html;

        return $html;
    }
}