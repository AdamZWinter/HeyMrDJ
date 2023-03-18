<?php
/**
 *  Model for DJ extends User
 *
 * @author Adam Winter
 */
class DJ extends User
{
    private $_bio;


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