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
     * Controller method for the DJ Dashboard route GET
     *
     * @return void
     */
    static function getDashboard($f3)
    {
        $f3->set('states', DataLayer::getStates());
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/dashboard.html");
    }
}