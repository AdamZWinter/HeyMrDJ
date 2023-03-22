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
     * JSON response includes an array of arrays in the data key
     * that is all of the songs in the universal library
     *
     * @return void
     */
    static function getSongs()
    {
        //create response object
        $response = new stdClass();
        $response->error = false;
        //$response->message[] = 'Response message: ';

        //        $dataArray = [];
        //        $dataArray[] = Array('Song2', 'Blur', '4:20');
        //        $dataArray[] = Array('Give It Away', 'RHCP', '4:20');

        //Get songs from database
        $dataLayer = new DataLayer($response);
        $songs = $dataLayer->getSongs();
        //$response->message = $songs;

        //Reorganize database results into array that will fit table on front end
        $dataArray = [];
        foreach ($songs as $song){
            $asArray = [];
            $asArray[] = $song['name'];
            $asArray[] = $song['artist'];
            $asArray[] = $song['length'];

            $dataArray[] = $asArray;
        }

        //Add data array to data key of the response
        $response->data = $dataArray;
        //$length = strlen($responseCopy);
        //header('Content-Length: '.$length);
        header('Content-type: application/json');

        //respond to client API call
        echo json_encode($response);
    }

    /**
     * Controller method for the Music / charts route GET
     * Route to page that displays music charts
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
