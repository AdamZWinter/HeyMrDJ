<?php

/**
 *  controller for GET and POST to the Events routes
 *
 * @param  $f3 $f3 = Base::instance()
 * @author Adam Winter
 */
class Events
{
    /**
     * Controller method for the Events route GET
     *
     * @return void
     */
    static function get()
    {
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/events.html");
    }

    /**
     * Controller method for the Events route POST
     * Ingests API call posting new event to be added to database
     * Response will be JSON with error and message keys
     * error key will be true if there was an error
     * otherwise the event was successfully inserted to the database
     *
     * @return void
     */
    static function post()
    {
        //create response object
        $responseObj = new stdClass();
        $responseObj->error = false;
        $responseObj->message = [];
        //echo $_POST['JSONpayload'];

        //Check that user is signed in
        if(!User::isSignedIn()) {
            $responseObj->error = true;
            $responseObj->message[] = 'You must be signed into your account to do this.';
            echo json_encode($responseObj);
            exit;
        }

        //load the posted data into an object for processing and validation
        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);
        //echo $postedObject->getJSONencoded();
        $postedObject->validNameGeneric();
        $postedObject->validState();
        $postedObject->validDate();

        //After validation create the Event object
        $objJSON = $postedObject->getDecodedObject();
        $event = new Event();

        $event->setName($objJSON->name);
        $event->setDj($_SESSION['user']->getEmail());
        $event->setState($objJSON->state);
        $event->setDateread($objJSON->date);
        $event->datereadToDate();
        $event->setPlaylist(0);
        $event->setRequestlist(0);

        //        var_dump($event);
        //        exit;

        //insert the event into the database
        $dataLayer = new DataLayer($responseObj);
        $eventId = $dataLayer->addEvent($event);

        //check for database errors
        if(!is_null($eventId) && $eventId > 0) {
            $responseObj->message = [];
            $responseObj->message[] = "Event id: $eventId created.";
        }else{
            $responseObj->error = true;
            $responseObj->message[] = "Error inserting event to database.";
            $responseObj->message[] = "Event ID is: $eventId";
        }

        //respond to the client
        echo json_encode($responseObj);
    }

    /**
     * Controller method for the Events /api/dashboard/getDJEvents route GET
     * Ingests API request for a list of events belonging to the signed-in DJ
     * Returns JSON encoded object with error, message, and data keys
     * error key will be true if there was an error
     * data key will contain and array of arrays, each with event data
     * The associated page displayed is viewed only by the DJ through the dashboard
     * and only shows the DJ their own events
     *
     * @return void
     */
    static function getByDJ()
    {
        //create the response object
        $response = new stdClass();
        $response->error = false;
        $response->message[] = 'response message';

        //check if user is signed in
        if(!User::isSignedIn()) {
            $response->error = true;
            $response->message[] = 'You must be signed into your account to do this.';
            echo json_encode($response);
            exit;
        }

        //                $dataArray = [];
        //                $dataArray[] = Array('Event Name', '2023-3-19', 'Requests');
        //                $dataArray[] = Array('one', 'two', 'three',);

        //Get the events data for the signed-in DJ from the database
        $dataLayer = new DataLayer();
        $events = $dataLayer->getEventsByDJ($_SESSION['user']->getEmail());

        //Format the database response to fit the display table
        $dataArray = [];
        foreach ($events as $id){
            $event = $dataLayer->getEventByID($id);
            $asArray = [];
            $asArray[] = $id;
            $asArray[] = $event->getName();
            $asArray[] = $event->getDateread();
            //$asArray[] = $event->getRequestlist();
            //var_dump($applicant);
            //            $asArray = $event->toArray();
            //            $asArray[6] = '<a href="'.$asArray[6].'">Github</a>';
            //            $asArray[9] = '<a href="applicant/'.$id.'">Biography</a>';
            //            $asArray[10] = '<a href="applicant/'.$id.'">Profile</a>';

            $dataArray[] = $asArray;
        }
        //        echo '<pre>';
        //        var_dump($dataArray);
        //        echo '</pre>';

        //put the data in the resonse
        $response->data = $dataArray;
        //$length = strlen($responseCopy);
        //header('Content-Length: '.$length);
        header('Content-type: application/json');

        //respond to the client
        echo json_encode($response);
    }
}
