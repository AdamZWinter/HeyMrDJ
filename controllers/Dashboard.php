<?php

/**
 *  controller for GET and POST to the Dashboard routes
 *
 * @param  $f3 $f3 = Base::instance()
 * @author Adam Winter
 */
class Dashboard
{
    /**
     * Controller method for the DJ Dashboard route GET
     * Page displays the DJ dashboard which displays a list of that
     * DJ's upcoming events, option to add events and link to settings
     *
     * @return void
     */
    static function getDashboard($f3)
    {
        $f3->set('states', DataLayer::getStates());
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/dashboard/dashboard.html");
    }

    /**
     * Controller method for the DJ Dashboard Event page route GET
     * When DJ is signed in, they can view their individual events
     * Page displays details of an individual event
     * including the playlist and the request list for the event
     *
     * @return void
     */
    static function getDashboardEventByID($f3)
    {
        $dataLayer = new DataLayer();
        $event = $dataLayer->getEventByID($f3->get('PARAMS.id'));
        $f3->set('event', $event);
        //        $viewData = [];
        //        $viewData['eventID'] = $event->getId();
        //        $f3->set('viewData', $viewData);
        //$f3->sync('SESSION');
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/dashboard/event.html");
    }

    static function getDJsettings($f3)
    {
        $view = new Template();
        echo $view->render("views/dashboard/settings.html");
    }

    /**
     * Controller method for the dashboard/settings route POST
     * Returns a JSON encoded API response to the front-end call
     * This response will include and error message under keys error and message
     * if there was an error
     * Otherwise, the DJ settings were successfully inserted / updated in the database
     *
     * @return void
     */
    static function postDJsettings()
    {
        //create a response object and initialize
        $responseObj = new stdClass();
        $responseObj->error = false;
        $responseObj->message = [];
        //echo $_POST['JSONpayload'];

        //Check if use is signed in and respond if not
        if(!User::isSignedIn()) {
            $responseObj->error = true;
            $responseObj->message[] = 'You must be signed into your account to do this.';
            echo json_encode($responseObj);
            exit;
        }

        //load the posted data into an object for processing and validation
        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);
        //var_dump($postedObject);
        $postedObject->validNameByField('djname');
        $postedObject->sanitize('bio');
        $objJSON = $postedObject->getDecodedObject();
        //echo json_encode($objJSON);

        //After validation add the new data to the DJ/User model
        $dj = $_SESSION['user'];
        $dj->setDJname($objJSON->djname);
        $dj->setBio($objJSON->bio);

        //Insert the new data into the database
        $dataLayer = new DataLayer($responseObj);
        $dataLayer->addDJinfo($dj);

        //Respond to the client API call
        echo json_encode($responseObj);
    }
}
