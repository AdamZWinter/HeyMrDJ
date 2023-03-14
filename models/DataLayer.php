<?php

class DataLayer
{

    private $_dbh;
    function __construct()
    {
        include $_SERVER['HOME'].'/conf.php';
        try {
            $this->_dbh = new PDO(DB_DRIVER, DB_USER, PASSWORD);
            //echo 'DB connection successful.';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function insertUser($user)
    {
        //$user = new User();
        $sql = "INSERT INTO `users`(`id`, `fname`, `lname`, `email`, `phone`, `state`, `photo`, `password`) 
                    VALUES (null, :fname, :lname, :email, :phone, :state, :photo, :password)";
        $stmt = $this->_dbh->prepare($sql);

        $lname = $user->getFname();
        $fname = $user->getLname();
        $email = $user->getEmail();
        $phone = $user->getPhone();
        $state = $user->getState();
        $photo = $user->getPhoto();
        $password = $user->getPassword();

        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':photo', $photo);
        $stmt->bindParam(':password', $password);


        $stmt->execute();
        if($stmt->rowCount() == 1) {
            return $this->_dbh->lastInsertId();
        }else{
            var_dump($stmt->errorInfo());
            return -1;
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
        if($stmt->rowCount() != 1){
            echo 'No such email address found';
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['isDJ'] == 1) {
            $user = new DJ();
        }else{
            $user = new User();
        }
        $user->constructFromDatabase($result);
        return $user;
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

}
