<?php

/**
 *  Model for User
 *
 * @author Adam Winter
 */
class User
{
    private $_id;
    private $_fname;
    private $_lname;
    private $_email;
    private $_phone;
    private $_state;
    private $_photo;
    private $_password;


    /**
     * @param $_fname  String first name, defaults to null
     * @param $_lname  String last name, defaults to null
     * @param $_email  String email, defaults to null
     * @param $_phone  String phone, defaults to null
     * @param $_state  String state, defaults to null
     */
    public function __construct($fname = null, $lname = null, $email = null, $phone = null, $state = null)
    {
        $this->_fname = $fname;
        $this->_lname = $lname;
        $this->_email = $email;
        $this->_phone = $phone;
        $this->_state = $state;
        $this->_photo = 'somebody.jpg';
    }

    /**
     * @return String first name
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @param String $fname
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @return String Last name
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * @param String $lname Last name
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * @return String email address
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param String $email Email address
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return String Phone number
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param String $phone Phone number
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * @return String State or providence of the USA
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @param String $state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * @return null
     */
    public function getPhoto()
    {
        return $this->_photo;
    }

    /**
     * @param null $photo
     */
    public function setPhoto($photo)
    {
        $this->_photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($clearText)
    {
        $peppered = $clearText.$GLOBALS['PEPPER'];
        $pwHash = password_hash($peppered, PASSWORD_DEFAULT);
        $this->_password = $pwHash;
    }

    public function passwordVerified($clearText)
    {
        $peppered = $clearText.$GLOBALS['PEPPER'];
        return password_verify($peppered, $this->getPassword());
    }



//    public function toArray(){
//        $applicantArray = [];
//        $applicantArray[] = $this->getId();
//        $applicantArray[] = $this->getFname();
//        $applicantArray[] = $this->getLname();
//        $applicantArray[] = $this->getEmail();
//        $applicantArray[] = $this->getPhone();
//        $applicantArray[] = $this->getState();
//        $applicantArray[] = $this->getGithub();
//        $applicantArray[] = $this->getExperience();
//        $applicantArray[] = $this->getRelocate();
//        $applicantArray[] = $this->getBio();
//        $applicantArray[] = $this->getPhoto();
//        return $applicantArray;
//    }

    public function constructFromDatabase($assoc)
    {
        $this->setId($assoc['id']);
        $this->setFname($assoc['fname']);
        $this->setLname($assoc['lname']);
        $this->setEmail($assoc['email']);
        $this->setPhone($assoc['phone']);
        $this->setState($assoc['state']);
        $this->setPhoto($assoc['photo']);
    }


}