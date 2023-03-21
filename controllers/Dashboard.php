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

    static function getDJsettings($f3){
        $view = new Template();
        echo $view->render("views/dashboard/settings.html");
    }

    /**
     * Controller method for the dashboard/settings route POST
     *
     * @return void
     */
    static function postDJsettings()
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
        //var_dump($postedObject);
        $postedObject->validNameByField('djname');
        $postedObject->sanitize('bio');
        $objJSON = $postedObject->getDecodedObject();
        //echo json_encode($objJSON);

        $dj = $_SESSION['user'];
        $dj->setDJname($objJSON->djname);
        $dj->setBio($objJSON->bio);

        $dataLayer = new DataLayer($responseObj);
        $dataLayer->addDJinfo($dj);

        echo json_encode($responseObj);
    }
}