<?php
/**
 *  controller for GET to the home route
 *
 * @author Adam Winter
 */
class HomePage
{
    /**
     * Controller method for the home route GET
     *
     * @return void
     */
    static function get()
    {
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/home.html");
    }

}
