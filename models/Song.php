<?php

class Song
{

    private $name;
    private $length;
    private $artist;

    //constructor with default for artist, name of the song and its length are required fields
    function __construct($name, $length, $artist)
    {
        $this->artist = $artist;
        $this->name = $name;
        $this->length = $length;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(String $name)
    {
        $this->name = $name;
    }

    /**
     * @return double
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param double $length
     */
    public function setLength(double $length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist(String $artist)
    {
        $this->artist = $artist;
    }



}