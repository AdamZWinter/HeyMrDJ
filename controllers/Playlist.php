<?php

/**
 * controller for GET to the playlist route
 *
 * @author Gavin Sherman
 */
class Playlist
{
    static function get(){
        $view = new Template();
        echo $view->render("views/playlist
        .html");
    }

    function getPlaylist(){

    }
}