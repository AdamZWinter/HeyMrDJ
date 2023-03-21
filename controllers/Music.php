<?php

/**
 *  controller for GET and POST to the Music routes
 *
 * @param  $f3 $f3 = Base::instance()
 * @author Adam Winter
 */
class Music
{
    /**
     * Controller method for the Music   api/getSongs route GET
     *
     * @return void
     */
    static function getSongs()
    {
        $response = new stdClass();
        $response->error = false;
        //$response->message[] = 'Response message: ';

//        $dataArray = [];
//        $dataArray[] = Array('Song2', 'Blur', '4:20');
//        $dataArray[] = Array('Give It Away', 'RHCP', '4:20');

        $dataLayer = new DataLayer($response);
        $songs = $dataLayer->getSongs();
        //$response->message = $songs;

        $dataArray = [];
        foreach ($songs as $song){
            $asArray = [];
            $asArray[] = $song['name'];
            $asArray[] = $song['artist'];
            $asArray[] = $song['length'];

            $dataArray[] = $asArray;
        }

        $response->data = $dataArray;
        //$length = strlen($responseCopy);
        //header('Content-Length: '.$length);
        header('Content-type: application/json');

        echo json_encode($response);
    }

    /**
     * Controller method for the Music / charts route GET
     *
     * @return void
     */
    static function getCharts()
    {
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/charts.html");
    }

    /**
     * Controller method for the Music route POST
     *
     * @return void
     */
    static function post()
    {
        //not used yet
    }
}