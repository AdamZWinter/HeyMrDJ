<?php

class DataLayer
{
    protected $_responseObj;
    private $_dbh;
    function __construct($stdClassObj = null)
    {
        if($stdClassObj == null){
            $this->_responseObj = new stdClass();
            $this->_responseObj->error = false;
        }else if(!is_a($stdClassObj, stdClass::class)){
            $this->_responseObj->error = true;
            $this->_responseObj->message = [];
            $this->_responseObj->message[] = 'You can only pass an object of type stdClass to DataLayer';
            echo json_encode($this->_responseObj);
            exit;
        }else{
            $this->_responseObj = $stdClassObj;
        }

        include $_SERVER['HOME'].'/conf.php';
        try {
            $this->_dbh = new PDO(DB_DRIVER, DB_USER, PASSWORD);
            $this->_responseObj->dbh = 'DB connection successful.';
            $this->_responseObj->message[] = 'DB connection successful.';
            //echo 'DB connection successful.';
        } catch (PDOException $e) {
            //echo $e->getMessage();
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'Failed to open database connection: '.$e->getMessage();
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    function insertUser($user)
    {
        //$user = new User();
        $sql = "INSERT INTO `users`(`id`, `fname`, `lname`, `email`, `phone`, `state`, `photo`, `password`, `isDJ`) 
                    VALUES (null, :fname, :lname, :email, :phone, :state, :photo, :password, :isDJ)";
        $stmt = $this->_dbh->prepare($sql);

        $fname = $user->getFname();
        $lname = $user->getLname();
        $email = $user->getEmail();
        $phone = $user->getPhone();
        $state = $user->getState();
        $photo = $user->getPhoto();
        $password = $user->getPassword();
        $isDJ = $user->isDJ();

        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':photo', $photo);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':isDJ', $isDJ);


        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $errorInfo = $stmt->errorInfo();
        if($errorInfo[0] != "00000"){
            //var_dump($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'DB error: ';
            if($errorInfo[1]){
                $this->_responseObj->message[] = $errorInfo[1];
            }
            if($errorInfo[2]){
                $this->_responseObj->message[] = $errorInfo[2];
            }
            echo json_encode($this->_responseObj);
            exit;
        }

        if($stmt->rowCount() == 1) {
            $this->_responseObj->message[] = "Successfully inserted new user.";
            return $this->_dbh->lastInsertId();
        }else{
            $this->_responseObj->error = true;
            $this->_responseObj->errorInfo = $stmt->errorInfo();
            $this->_responseObj->message[] = 'Failed to insert new user to database: Error info available as array in errorInfo key ';
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    function getAllUserIDs()
    {
        $sql = "SELECT id FROM users";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        //var_dump($results);
        $arrayResults = [];
        foreach ($results as $row){
            $arrayResults[] = $row[0];
        }
        return $arrayResults;
    }

    function getUserByID($id)
    {
        $sql = "SELECT * FROM users WHERE `id` = :id";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['isDJ'] == 1) {
            $user = new DJ();
        }else{
            $user = new User();
        }
        $user->constructFromDatabase($result);
        return $user;
    }

    function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE `email` = :email";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $errorInfo = $stmt->errorInfo();
        if($errorInfo[0] != "00000"){
            //var_dump($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'DB error: ';
            if($errorInfo[1]){
                $this->_responseObj->message[] = message.$errorInfo[1];
            }
            if($errorInfo[2]){
                $this->_responseObj->message[] = message.$errorInfo[2];
            }
            echo json_encode($this->_responseObj);
            exit;
        }
        if($stmt->rowCount() != 1){
            $this->_responseObj->error = false;
            $this->_responseObj->message[] = 'No such email address found';
            $this->_responseObj->emailExists = false;
            echo json_encode($this->_responseObj);
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['isDJ'] == 1) {
            $user = new DJ();
        }else{
            $user = new User();
        }
        $user->constructFromDatabase($result);
        $this->_responseObj->message[] = "Successfully constructed user.";
        return $user;
    }

    function getPasswordByEmail($email)
    {
        $sql = "SELECT `password` FROM users WHERE `email` = :email";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $errorInfo = $stmt->errorInfo();
        if($errorInfo[0] != "00000"){
            //var_dump($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'DB error: ';
            if($errorInfo[1]){
                $this->_responseObj->message[] = message.$errorInfo[1];
            }
            if($errorInfo[2]){
                $this->_responseObj->message[] = message.$errorInfo[2];
            }
            echo json_encode($this->_responseObj);
            exit;
        }
        if($stmt->rowCount() != 1){
            $this->_responseObj->error = false;
            $this->_responseObj->message[] = 'No such email address found';
            $this->_responseObj->emailExists = false;
            echo json_encode($this->_responseObj);
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }

    static function getStates()
    {
        $noKeys = array("Alabama", "Alaska", "American Samoa", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Guam", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Minor Outlying Islands", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Northern Mariana Islands", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "U.S. Virgin Islands", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming");
        $states = [];

        for($i = 0; $i < count($noKeys); $i++){
            //echo $states[$i].PHP_EOL;
            $states[$i+1] = $noKeys[$i];
        }
        return $states;
    }

    function addEvent($event)
    {
        //$event = new Event();
        $sql = "INSERT INTO `events`(`id`, `name`, `dj`, `state`, `date`, `dateread`) 
                    VALUES (null, :name, :dj, :state, :date, :dateread)";
        $stmt = $this->_dbh->prepare($sql);

        $name = $event->getName();
        $dj = $event->getDj();
        $state = $event->getState();
        $date = $event->getDate();
        $dateread = $event->getDateread();

        //var_dump($event->getName());
        //exit;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':dj', $dj);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':dateread', $dateread);


        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $errorInfo = $stmt->errorInfo();
        if($errorInfo[0] != "00000"){
            //var_dump($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'DB error: ';
            if($errorInfo[1]){
                $this->_responseObj->message[] = $errorInfo[1];
            }
            if($errorInfo[2]){
                $this->_responseObj->message[] = $errorInfo[2];
            }
            echo json_encode($this->_responseObj);
            exit;
        }

        if($stmt->rowCount() == 1) {
            $this->_responseObj->message[] = 'Successfully inserted.';
            return $this->_dbh->lastInsertId();
        }else{
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'Database error:  Row count is not equal to 1 ';
            echo json_encode($this->_responseObj);
            exit;
        }
    }

}
