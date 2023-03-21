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
    private $_isDJ;


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

    /** Standard getter
     * @return String first name
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /** Standard setter
     * @param String $fname User first name
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**Standard Getter
     * @return String User Last name
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /** Standard Setter
     * @param String $lname User Last name
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /** Standard Getter
     * @return String User email address
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /** Standard Setter
     * @param String $email User Email address
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**Standard Getter
     * @return String Phone number
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /** Standard Setter
     * @param String $phone Phone number
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /** Standard Getter
     * @return String State or providence of the USA
     */
    public function getState()
    {
        return $this->_state;
    }

    /** Standard setter
     * @param String $state user state of residence
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /** Standard Getter
     * @return String Filename of user photo
     */
    public function getPhoto()
    {
        return $this->_photo;
    }

    /** Standard setter
     * @param String $photo Filename of user photo
     */
    public function setPhoto($photo)
    {
        $this->_photo = $photo;
    }

    /** Standard getter
     * @return int user id
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Standard setter
     * @param int $id user id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /** Standard getter
     * @return String Stored hash of password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /** Setter: hashes clear text password and puts result in instance field
     * @param String $clearText clear text password
     */
    public function setPassword($clearText)
    {
        $peppered = $clearText.$GLOBALS['PEPPER'];
        $pwHash = password_hash($peppered, PASSWORD_DEFAULT);
        $this->_password = $pwHash;
    }

    /** Verifies the provided clear-text password against the password hash of the user
     * GLOBALS array much contain PEPPER key, which should be defined in configuration
     * @param $clearText String The clear text password of the user
     * @return bool
     */
    public function passwordVerified($clearText)
    {
        $peppered = $clearText.$GLOBALS['PEPPER'];
        return password_verify($peppered, $this->getPassword());
    }

    //This was not used but may be later.
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

    /** User should be instantiated without parameters
     * Then associative array with row from users database
     * should be passed to this method to reconstruct the user
     * @param $assoc Array Associative array as result of $stmt->fetch(PDO::FETCH_ASSOC) from users table
     * @return void
     */
    public function constructFromDatabase($assoc)
    {
        $this->setId($assoc['id']);
        $this->setFname($assoc['fname']);
        $this->setLname($assoc['lname']);
        $this->setEmail($assoc['email']);
        $this->setPhone($assoc['phone']);
        $this->setState($assoc['state']);
        $this->setPhoto($assoc['photo']);
        $this->setIsDJ($assoc['isDJ']);
    }

    /**
     * @return bool Whether the user is also a DJ
     */
    public function isDJ()
    {
        return $this->_isDJ;
    }

    /**
     * @param mixed $binary one or zero
     */
    public function setIsDJ($binary)
    {

        $this->_isDJ = $binary == 1;
    }

    /**
     * @return bool Whether the user has an active session
     */
    public static function isSignedIn(){
        if(!array_key_exists('user', $_SESSION)){
            return false;
        }else{
            return true;
        }
    }



}