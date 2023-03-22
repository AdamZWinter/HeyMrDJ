<?php

/**
 * controller for GET to the playlist route
 *
 * @author Gavin Sherman
 */
class Playlist
{
//    static function get($f3){
//        $dataLayer = new DataLayer();
//        $f3->set('songs', $dataLayer->getSongs());
//
//        $view = new Template();
//        echo $view->render("views/playlist.html");
//    }

    static function post(){
        //not being used yet
    }

    static function getPlaylistByID($f3)
    {
        $dataLayer = new DataLayer();
        $songs = $dataLayer->getPlaylistByID($f3->get('PARAMS.songs'));
        $f3->set('songs', $songs);


        //Instantiate a view
        $view = new Template();
        echo $view->render("views/playlist.html");
    }
}