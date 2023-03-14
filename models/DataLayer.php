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
        $sql = "INSERT INTO `users`(`id`, `fname`, `lname`, `email`, `phone`, `state`, `github`, 
                                        `experience`, `relocate`, `bio`, `photo`, `mailing_lists_signup`, `mailing_lists_subscriptions`) 
                    VALUES (null, :fname, :lname, :email, :phone, :state, :github, :experience, :relocate, :bio, :photo, :subscribed, :lists)";
        $stmt = $this->_dbh->prepare($sql);

        $lname = $user->getFname();
        $fname = $user->getLname();
        $email = $user->getEmail();
        $phone = $user->getPhone();
        $state = $user->getState();
        //$github = $user->getGithub();
        //$experience = $user->getExperience();
        //$relocate = $applicant->getRelocate() == 'yes' ? 1 : 0;
        //$bio = $applicant->getBio();
        $photo = $user->getPhoto();
        //$subscribed = is_a($applicant, Applicant_SubscribedToLists::class) ? 1 : 0;
        //$lists = $subscribed ? $applicant->getListsString() : "none";

        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':state', $state);
//        $stmt->bindParam(':github', $github);
//        $stmt->bindParam(':experience', $experience);
//        $stmt->bindParam(':relocate', $relocate);
//        $stmt->bindParam(':bio', $bio);
//        $stmt->bindParam(':photo', $photo);
//        $stmt->bindParam(':subscribed', $subscribed);
//        $stmt->bindParam(':lists', $lists);

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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['isDJ'] == 1) {
            $user = new DJ();
        }else{
            $user = new User();
        }
        $user->constructFromDatabase($result);
        return $user;
    }

}
