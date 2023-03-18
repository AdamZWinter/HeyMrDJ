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