<<<<<<< HEAD
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
        //$songs = $dataLayer->getPlaylistByID($f3->get('PARAMS.songs'));
        $songs = [1, 2, 3];
        $f3->set('songs', $songs);


        //Instantiate a view
        $view = new Template();
        echo $view->render("views/playlist.html");
    }
}
=======
<?php

/**
 * controller for GET to the playlist route
 *
 * @author Gavin Sherman
 */
class Playlist
{
    static function get($f3)
    {
        $dataLayer = new DataLayer();
        $f3->set('songs', $dataLayer->getSongs());

        $view = new Template();
        echo $view->render("views/playlist.html");
    }

    static function post()
    {
        //not being used yet
    }
}
>>>>>>> 68d02aa89cfbbf0c6b00c9332e8966ba2fafe062
