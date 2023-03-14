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
        $obj = new stdClass();
        $obj->error = false;

        $postedObject = new PostedObj($_POST['JSONpayload'], $obj);

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

        $dataLayer = new DataLayer();
        $dataLayer->insertUser($user);

        unset($userObjJSON);
        $_POST['JSONpayload'] = null;

//        $_SESSION["user"] = $user;  //only after sign in

        setcookie('fname', $_SESSION["fname"]);
        setcookie('lname', $_SESSION["lname"]);
        setcookie('email', $_SESSION["email"]);
        setcookie('phone', $_SESSION["phone"]);
        setcookie('state', $_SESSION["state"]);

        //respond to the client
        echo json_encode($postedObject->getObj());
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
        $obj = new stdClass();
        $obj->error = false;

        $postedObject = new PostedObj($_POST['JSONpayload'], $obj);
        $email = $postedObject->validEmail();

        $dataLayer = new DataLayer();
        $user = $dataLayer->getUserByEmail($email);
        echo var_dump($user);

        //respond to the client
        //echo json_encode($postedObject->getObj());
        unset($postedObject);
    }

}
