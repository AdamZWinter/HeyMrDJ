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
        $dataLayer = new DataLayer();
        $event = $dataLayer->getDJ($_SESSION['user']);
    }

}