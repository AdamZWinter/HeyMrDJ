<?php

/**
 * All methods that require database queries
 *
 * @authors Adam Winter, Gavin Sherman
 */
class DataLayer
{
    // responseObj: Many API calls will be made that require a JSON encoded response
    //This is the object that will be JSON encoded and returned
    //This object may be created elsewhere and passed to the class
    //in order to collect additional response data
    //If it is not passed to the class upon instantiation
    //then it wil be created by the constructor
    protected $_responseObj;
    private $_dbh;  //The database connection object

    /**
     * Constructor
     *
     * @param $stdClassObj stdClass Object of type stdClass, should include keys error and message[] (Optional)
     */
    function __construct($stdClassObj = null)
    {
        if($stdClassObj == null) {
            $this->_responseObj = new stdClass();
            $this->_responseObj->error = false;
        }else if(!is_a($stdClassObj, stdClass::class)) {
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

    //errorsinfo is array from PDO statement->errorInfo()
    private function handleStmtErrorsAPI($errorInfo)
    {
        if($errorInfo[0] != "00000") {
            //var_dump($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'DB error: ';
            if($errorInfo[1]) {
                $this->_responseObj->message[] = $errorInfo[1];
            }
            if($errorInfo[2]) {
                $this->_responseObj->message[] = $errorInfo[2];
            }
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    /**
     * Inserts new User into database
     *
     * @param $user User New User to insert to database
     */
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
        $this->handleStmtErrorsAPI($stmt->errorInfo());

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

    /**
     * Get array of all user id's
     *
     * @return array An array of all the user ID's of all the users
     */
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

    /**
     * Returns the DJ or User class object from the provided user id
     *
     * @param  $id int the id of the DJ or User you want to instantiate and object for
     * @return DJ|User The DJ or User object from the provided id
     */
    function getUserByID($id)
    {
        $sql = "SELECT * FROM users WHERE `id` = :id";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['isDJ'] == 1) {
            $user = new DJ();
        }else{
            $user = new User();
        }
        $user->constructFromDatabase($result);
        return $user;
    }

    /**
     * Returns the DJ or User class object from the provided user email
     *
     * @param  $email String The email address of the DJ or User you want to instantiate and object for
     * @return DJ|User|void The DJ or User object from the provided email address
     */
    function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE `email` = :email";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());

        if($stmt->rowCount() != 1) {
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
        if($user->isDJ()) {
            $this->getDJinfo($user);
        }
        $this->_responseObj->message[] = "Successfully constructed user.";
        return $user;
    }

    /**
     * Sets the additional/extended fields (from User) for DJ, from database
     *
     * @param  $dj DJ The DJ object that does not have the additional DJ fields set yet
     * @return void
     */
    function getDJinfo($dj)
    {
        $sql = "SELECT * FROM dj_info WHERE `id` = :id";
        $stmt = $this->_dbh->prepare($sql);
        $id = $dj->getId();
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());

        if($stmt->rowCount() != 1) {
            //            $dj->setDJname('');
            //            $dj->setBio('');
        }else{
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $dj->setDJname($result['djname']);
            $dj->setBio($result['bio']);
        }
    }

    /**
     * For use in updating or creating DJ info:
     * the additional/extended fields (from User) for DJ
     * database query updates on existing key: id
     *
     * @param  $dj DJ The DJ object that does not have the additional DJ fields set yet
     * @return void
     */
    function addDJinfo($dj)
    {
        $sql = "INSERT INTO `dj_info` (`id`, `djname`, `bio`)
                    VALUES (:id, :djname, :bio) ON DUPLICATE KEY UPDATE djname=VALUES(djname), bio=VALUES(bio)";
        $stmt = $this->_dbh->prepare($sql);

        $id = $dj->getId();
        $djname = $dj->getDJname();
        $bio = $dj->getBio();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':djname', $djname);
        $stmt->bindParam(':bio', $bio);

        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $this->_responseObj->message[] = "Successfully inserted settings.";
        }else{
            //https://www.php.net/manual/en/pdo.errorinfo.php
            $this->handleStmtErrorsAPI($stmt->errorInfo());
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'Affected row count: '.$stmt->rowCount();
            $this->_responseObj->message[] = 'Error adding or updating DJ info settings in DataLayer ';
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    /**
     * Fetches the password hash of the user by the provided email address
     *
     * @param  $email String The email addres of the user
     * @return mixed|void
     */
    function getPasswordByEmail($email)
    {
        $sql = "SELECT `password` FROM users WHERE `email` = :email";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());

        if($stmt->rowCount() != 1) {
            $this->_responseObj->error = false;
            $this->_responseObj->message[] = 'No such email address found';
            $this->_responseObj->emailExists = false;
            echo json_encode($this->_responseObj);
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }

    /**
     * Returns index key value pair array of states ex.  1 => Alabama
     *
     * @return array
     */
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

    /**
     * Inserts new event into the database
     *
     * @param  $event Event The event to add to the database
     * @return String JSON encoded response with error and message[] keys
     */
    function addEvent($event)
    {
        //$event = new Event();
        $sql = "INSERT INTO `events`(`id`, `name`, `dj`, `state`, `date`, `dateread`, `playlist`, `requestlist`) 
                    VALUES (null, :name, :dj, :state, :date, :dateread, :playlist, :requestlist)";
        $stmt = $this->_dbh->prepare($sql);

        $name = $event->getName();
        $dj = $event->getDj();
        $state = $event->getState();
        $date = $event->getDate();
        $dateread = $event->getDateread();
        $playlist = $event->getPlaylist();
        $requestlist = $event->getRequestlist();

        //var_dump($event->getName());
        //exit;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':dj', $dj);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':dateread', $dateread);
        $stmt->bindParam(':playlist', $playlist);
        $stmt->bindParam(':requestlist', $requestlist);


        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());

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

    function addSong($song)
    {

        //1. Define the query
        $sql= "INSERT INTO `playlist`(`name`, `length`, `artist`) 
                VALUES (:name, :length, :artist)";

        //2. Prepare the Statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters
        $statement->bindParam(':name', $song->getName());
        $statement->bindParam(':length', $song->getLength());
        $statement->bindParam(':artist', $song->getArtist());
    }

    function getSongs()
    {

        //1. Define the query
        $sql = "SELECT * FROM songs";

        //2. prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. execute the query
        $statement->execute();
        $this->handleStmtErrorsAPI($statement->errorInfo());

        //5. process the results
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reconstructs and Event object from the database by event id
     *
     * @param  $id int The event ID to reconstruct from the database
     * @return Event|void  The Event object
     */
    function getEventByID($id)
    {
        $sql = "SELECT * FROM events WHERE `id` = :id";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        //https://www.php.net/manual/en/pdo.errorinfo.php
        $this->handleStmtErrorsAPI($stmt->errorInfo());
        if($stmt->rowCount() != 1) {
            $this->_responseObj->error = false;
            $this->_responseObj->message[] = 'No such event found';
            echo json_encode($this->_responseObj);
            exit;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $event = new Event();
        $event->constructFromDatabase($result);
        $this->_responseObj->message[] = "Successfully constructed event.";
        return $event;
    }

    /**
     * Gets data for all events owned by the DJ indicated by email address
     *
     * @param  $email String The email address of the DJ
     * @return array An array of arrays that are the event data
     */
    function getEventsByDJ($email)
    {
        $sql = "SELECT `id` FROM events WHERE `dj` = :email";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $results = $stmt->fetchAll();
        //var_dump($results);
        $arrayResults = [];
        foreach ($results as $row){
            $arrayResults[] = $row[0];
        }
        return $arrayResults;
    }

    function getPlaylistByID($id){
        $sql = "SELECT 'songs' FROM playlists WHERE id = :id";
        $stmt = $this->_dbh->prepare($sql);
//        $id = 1;                                //For testing only *************************************************
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $this->handleStmtErrorsAPI($stmt->errorInfo());

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        var_dump($result);

        $playlistString = $result['songs'];

        $songsArray = explode(", ", $playlistString);

        return $songsArray;
    }

    function getSongByID($id)
    {
        //1. Define the query
        $sql = "SELECT * from songs WHERE id = :id";

        //2. prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters
        $statement->bindParam(':id', $id);

        //4. execute the query
        $statement->execute();
        $this->handleStmtErrorsAPI($statement->errorInfo());

        //5. process the results
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

}
