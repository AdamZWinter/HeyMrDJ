<?php
/**
 *  Model for DJ extends User
 *  Additional fields for biography and DJ Name
 *
 * @author Adam Winter
 */
class DJ extends User
{
    private $_bio;
    private $_DJname;

    /**
     * @return mixed
     */
    public function getDJname()
    {
        return $this->_DJname;
    }

    /**
     * @param mixed $DJname
     */
    public function setDJname($DJname)
    {
        $this->_DJname = $DJname;
    }




    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->_bio;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }

}