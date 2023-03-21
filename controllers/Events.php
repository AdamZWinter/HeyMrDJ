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
     *
     * @return void
     */
    static function post()
    {
        $responseObj = new stdClass();
        $responseObj->error = false;
        $responseObj->message = [];
        //echo $_POST['JSONpayload'];

        if(!User::isSignedIn()){
            $responseObj->error = true;
            $responseObj->message[] = 'You must be signed into your account to do this.';
            echo json_encode($responseObj);
            exit;
        }

        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);
        //echo $postedObject->getJSONencoded();
        $postedObject->validNameGeneric();
        $postedObject->validState();
        $postedObject->validDate();

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

        $dataLayer = new DataLayer($responseObj);
        $eventId = $dataLayer->addEvent($event);

        if(!is_null($eventId) && $eventId > 0){
            $responseObj->message = [];
            $responseObj->message[] = "Event id: $eventId created.";
        }else{
            $responseObj->error = true;
            $responseObj->message[] = "Error inserting event to database.";
            $responseObj->message[] = "Event ID is: $eventId";
        }

        echo json_encode($responseObj);
    }

    /**
     * Controller method for the Events list page route GET
     * The page displayed is viewed only by the DJ through the dashboard
     * and only shows the DJ their own events
     *
     * @return void
     */
    static function getByDJ()
    {
        $response = new stdClass();
        $response->error = false;
        $response->message[] = 'response message';

        if(!User::isSignedIn()){
            $response->error = true;
            $response->message[] = 'You must be signed into your account to do this.';
            echo json_encode($response);
            exit;
        }

//                $dataArray = [];
//                $dataArray[] = Array('Event Name', '2023-3-19', 'Requests');
//                $dataArray[] = Array('one', 'two', 'three',);

        $dataLayer = new DataLayer();
        $events = $dataLayer->getEventsByDJ($_SESSION['user']->getEmail());

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

        $response->data = $dataArray;
        //$length = strlen($responseCopy);
        //header('Content-Length: '.$length);
        header('Content-type: application/json');

        echo json_encode($response);
    }
}