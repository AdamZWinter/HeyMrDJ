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
     * 
     * Standard getter
     *
     * @return String DJ name
     */
    public function getDJname()
    {
        return $this->_DJname;
    }

    /**
     * 
     * Standard setter
     *
     * @param String $DJname DJ name
     */
    public function setDJname($DJname)
    {
        $this->_DJname = $DJname;
    }


    /**
     * 
     * Standard getter
     *
     * @return String DJ biography
     */
    public function getBio()
    {
        return $this->_bio;
    }

    /**
     * 
     * Standard setter
     *
     * @param String $bio DJ biography
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }

}
