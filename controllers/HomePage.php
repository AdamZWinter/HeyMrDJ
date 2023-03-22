<?php
/**
 *  controller for GET to the home route
 *
 * @author Adam Winter
 */
class HomePage
{
    /**
     * Controller method for the home route GET
     *
     * @return void
     */
    static function get()
    {
        //$user = $_SESSION['user'];
        //var_dump($user);
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/home.html");
    }

    /**
     * Controller method for the register route GET
     *
     * @return void
     */
    static function getRegister($f3)
    {
        $f3->set('states', DataLayer::getStates());
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/register.html");
    }

    /**
     * Controller method for the register route POST
     * Ingests an API call POST with data contained in json object JSONpayload
     * Data should include the user information required to register new user
     * Keys required in object:  fname, lname, email, phone, state, password, isDJ
     * Response will be json encoded and includes error and message keys
     * error key will be true if there was an error
     * Otherwise, the new user has successfully been entered in the database
     *
     * @return void
     */
    static function postRegister($f3)
    {
        //initialize the response object
        $responseObj = new stdClass();
        $responseObj->error = false;

        //ingest, parse and validate the posted data
        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);

        $postedObject->validName();
        $postedObject->validEmail();
        $postedObject->validPhone();
        $postedObject->validState();
        $postedObject->notBatman();

        //After validation, create the new user object
        $userObjJSON = $postedObject->getDecodedObject();
        $user = new User();

        $user->setFname($userObjJSON->fname);
        $user->setLname($userObjJSON->lname);
        $user->setEmail($userObjJSON->email);
        $user->setPhone($userObjJSON->phone);
        $user->setState($userObjJSON->state);
        $user->setPassword($userObjJSON->password);
        $user->setIsDJ($userObjJSON->isDJ);

        //insert the new user into the database
        $dataLayer = new DataLayer($responseObj);
        $dataLayer->insertUser($user);

        //clear clear-text passwords from memory
        unset($userObjJSON);
        $_POST['JSONpayload'] = null;

        //respond to the client
        echo json_encode($responseObj);
        unset($postedObject);  //remove clear txt passwords for memory
    }

    /**
     * Controller method for the register route GET
     *
     * @return void
     */
    static function getSignIn($f3)
    {
        //Instantiate a view
        $view = new Template();
        echo $view->render("views/signIn.html");
    }

    /**
     * Controller method for the signIn route POST
     * Ingests an API call POST with data contained in json object JSONpayload
     * Data should include the user information required to sign in
     * Keys required in object:  email, password
     * Response will be json encoded and includes error and message keys
     * error key will be true if there was an error
     * Otherwise, the new user has successfully signed in and session is created
     * User will be entered to the SESSION key 'user'
     *
     * @return void
     */
    static function postSignIn($f3)
    {
        //create resonse object
        $responseObj = new stdClass();
        $responseObj->error = false;

        //ingest, parse, and validate posted data
        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);
        $email = $postedObject->validEmail();
        unset($postedObject);

        //Get user from email in order to compare password hashes
        $dataLayer = new DataLayer($responseObj);
        $user = $dataLayer->getUserByEmail($email);

        // This would be a redundant way of going about this
        // but I want very strict handling of the password in clear text form
        $postObj = json_decode($_POST['JSONpayload']);
        $peppered = $postObj->password.$GLOBALS['PEPPER'];  //PEPPER is found in the configuration file next to database credentials
        unset($postObj);
        unset($_POST['JSONpayload']);
        if(password_verify($peppered, $dataLayer->getPasswordByEmail($email))) {
            $_SESSION["user"] = $user;  //User Session should only be created here with successful sign in
            setcookie('fname', $user->getFname());
            setcookie('lname', $user->getLname());
            //            setcookie('email', $_SESSION["email"]);
            //            setcookie('phone', $_SESSION["phone"]);
            //            setcookie('state', $_SESSION["state"]);
        }else{
            $responseObj->error = true;
            $responseObj->message[] = 'Ah ah ah, you didn say the magic word';
        }

        // for testing
        //$responseObj->error = true;
        //$responseObj->message[] = 'Testing: ';
        //var_dump($user);

        //respond to the client
        //var_dump($responseObj);
        echo json_encode($responseObj);
    }

}
