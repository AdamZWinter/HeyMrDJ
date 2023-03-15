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
     *
     * @return void
     */
    static function postRegister($f3)
    {
        $responseObj = new stdClass();
        $responseObj->error = false;

        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);

        $postedObject->validName();
        $postedObject->validEmail();
        $postedObject->validPhone();
        $postedObject->validState();
        $postedObject->notBatman();

        $userObjJSON = $postedObject->getDecodedObject();
        $user = new User();

        $user->setFname($userObjJSON->fname);
        $user->setLname($userObjJSON->lname);
        $user->setEmail($userObjJSON->email);
        $user->setPhone($userObjJSON->phone);
        $user->setState($userObjJSON->state);
        $user->setPassword($userObjJSON->password);

        $dataLayer = new DataLayer($responseObj);
        $dataLayer->insertUser($user);

        unset($userObjJSON);
        $_POST['JSONpayload'] = null;

        //respond to the client
        echo json_encode($responseObj);
        unset($postedObject);
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
     * Controller method for the Sign In route POST
     *
     * @return void
     */
    static function postSignIn($f3)
    {
        $responseObj = new stdClass();
        $responseObj->error = false;

        $postedObject = new PostedObj($_POST['JSONpayload'], $responseObj);
        $email = $postedObject->validEmail();
        unset($postedObject);

        $dataLayer = new DataLayer($responseObj);
        $user = $dataLayer->getUserByEmail($email);

        // This would be a redundant way of going about this
        // but I want very strict handling of the password in clear text form
        $postObj = json_decode($_POST['JSONpayload']);
        $peppered = $postObj->password.$GLOBALS['PEPPER'];
        unset($postObj);
        unset($_POST['JSONpayload']);
        if(password_verify($peppered, $dataLayer->getPasswordByEmail($email))){
            $_SESSION["user"] = $user;  //only after successful sign in
            setcookie('fname', $user->getFname());
            setcookie('lname', $user->getLname());
//            setcookie('email', $_SESSION["email"]);
//            setcookie('phone', $_SESSION["phone"]);
//            setcookie('state', $_SESSION["state"]);
        }else{
            $responseObj->error = true;
            $responseObj->message = 'Ah ah ah, you didn say the magic word';
        }

        // for testing
        //$responseObj->error = true;
        //$responseObj->message = 'Testing: '.$responseObj->message. '  Email: '.$email;
        //var_dump($user);

        //respond to the client
        //var_dump($responseObj);
        echo json_encode($responseObj);
    }

}
