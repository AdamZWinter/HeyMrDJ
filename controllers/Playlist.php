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
        //$songs = [1, 2, 3];

        //$songs = $dataLayer->getSongByID();
//        $f3->set('songs', $songs);
        //$playlistArray = $dataLayer->getPlaylistByID(1);

        //var_dump($playlistArray);

        //Instantiate a view
        $view = new Template();
        echo $view->render("views/playlist.html");
    }



    static function getSongsByEventID(){
        //create response object
        $response = new stdClass();
        $response->error = false;
        //$response->message[] = 'Response message: ';

        //        $dataArray = [];
        //        $dataArray[] = Array('Song2', 'Blur', '4:20');
        //        $dataArray[] = Array('Give It Away', 'RHCP', '4:20');

        //Get songs from database
        $dataLayer = new DataLayer($response);

        $songs = $dataLayer->getPlaylistByID(1);

        //var_dump($songs);

        //$songs = [1,2,3];
//        $songs[] = $dataLayer->getSongByID(1);
//        $songs[] = $dataLayer->getSongByID(3);

        //var_dump($songs);

        //Reorganize database results into array that will fit table on front end
        $dataArray = [];
        foreach ($songs as $id){

            $song = $dataLayer->getSongByID($id);
            //var_dump($song['name']);

            $asArray = [];
            $asArray[] = $song['name'];
            $asArray[] = $song['artist'];
            $asArray[] = $song['length'];

            $dataArray[] = $asArray;
        }

        //Add data array to data key of the response
        $response->data = $dataArray;

        header('Content-type: application/json');

        //respond to client API call
        echo json_encode($response);
    }
}

