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
        //not used yet
    }
}