<?php
/**
 *  Model for Event
 *
 * @author Adam Winter
 */
class Event
{
    private $_id;
    private $_name;
    private $_dj;
    private $_state;
    private $_date;
    private $_dateread;
    private $_playlist;
    private $_requestlist;


    /** Constructor for Event
     * @param $name String Name of event defaults to null
     * @param $dj String email address of DJ defaults to null
     * @param $state String state where event will be held defaults to null
     * @param $dateread String YYYY-mm-dd format defaults to null
     */
    public function __construct($name = null, $dj = null, $state = null, $dateread = null)
    {
        $this->_name = $name;
        $this->_dj = $dj;
        $this->_state = $state;
        $this->_dateread = $dateread;
    }

    /**  Constructs object by passing associative array
     *  from selecting row from events table in database
     *
     * @param $assoc  Array Associative array that is a single result row
     * from selecting from events table
     * @return void
     */
    public function constructFromDatabase($assoc)
    {
        $this->setId($assoc['id']);
        $this->setName($assoc['name']);
        $this->setDj($assoc['dj']);
        $this->setState($assoc['state']);
        $this->setDate($assoc['date']);
        $this->setDateread($assoc['dateread']);
        $this->setPlaylist($assoc['playlist']);
        $this->setRequestlist($assoc['requestlist']);
    }

    /**
     * @return int event ID auto incremented by database
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id event ID auto incremented by database
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed name of event
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name name of event
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return String email address of DJ for event
     */
    public function getDj()
    {
        return $this->_dj;
    }

    /**
     * @param String $dj email address of DJ for event
     */
    public function setDj($dj)
    {
        $this->_dj = $dj;
    }

    /**
     * @return String state from DataLayer::getstates()
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @param String $state state from DataLayer::getstates()
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * @return int unix date
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param int $date unix date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * @return String human-readable date as returned from html date type input
     * YYYY-mm-dd format
     */
    public function getDateread()
    {
        return $this->_dateread;
    }

    /**
     * @param String $dateread human-readable date as returned from html date type input
     * YYYY-mm-dd format
     */
    public function setDateread($dateread)
    {
        $this->_dateread = $dateread;
    }

    public function datereadToDate(){
        $this->_date = strtotime($this->getDateread());
    }

    public function dateToDateread(){
        $this->_dateread = date(U, $this->getDate());
    }

    /**
     * @return int ID of the playlist
     */
    public function getPlaylist()
    {
        return $this->_playlist;
    }

    /**
     * @param int $playlist ID of the playlist
     */
    public function setPlaylist($playlist)
    {
        $this->_playlist = $playlist;
    }

    /**
     * @return int ID of the request list
     */
    public function getRequestlist()
    {
        return $this->_requestlist;
    }

    /**
     * @param int $requestlist ID of the request list
     */
    public function setRequestlist($requestlist)
    {
        $this->_requestlist = $requestlist;
    }



}