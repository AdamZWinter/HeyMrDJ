<?php
class PostedObj
{
    protected $decodedObj;  //The decoded object sent from the client
    protected $obj;         //The object that will be json encoded and returned to the client.
    function __construct($JSONpayload, $obj)
    {
        $this->decodedObj = json_decode($JSONpayload);
        $this->obj = $obj;
    }

    function getJSONencoded()
    {
        return json_encode($this->decodedObj);
    }

    function getDecodedObject()
    {
        return $this->decodedObj;
    }

    function getObj()
    {
        return $this->obj;
    }

    public function validName()
    {
        if(empty($this->decodedObj->fname) || empty($this->decodedObj->lname)) {
            $this->obj->error = true;
            $this->obj->message = "First and Last names are required";
            echo json_encode($this->obj);
            exit;
        }
        if(!ctype_alpha($this->decodedObj->fname) || !ctype_alpha($this->decodedObj->lname)) {
            $this->obj->error = true;
            $this->obj->message = "Names can only contain letters";
            echo json_encode($this->obj);
            exit;
        }
    }//end function validName

    public function validEmail()
    {
        if(empty($this->decodedObj->email)) {
            $this->obj->error = true;
            $this->obj->message = "Email address is required.";
            echo json_encode($this->obj);
            exit;
        }
        if(!filter_var($this->decodedObj->email, FILTER_VALIDATE_EMAIL)) {
            $this->obj->error = true;
            $this->obj->message = "Invalid email address.";
            echo json_encode($this->obj);
            exit;
        }
        $this->obj->validEmail = true;
        return $this->decodedObj->email;
    }

    public function validPhone()
    {
        if(empty($this->decodedObj->phone)) {
            $this->obj->error = true;
            $this->obj->message = "Phone number is required.";
            echo json_encode($this->obj);
            exit;
        }
        $this->decodedObj->phone = filter_var($this->decodedObj->phone, FILTER_SANITIZE_STRING);
        if(!preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/", $this->decodedObj->phone)) {
            $this->obj->error = true;
            $this->obj->message = "Invalid phone number format.";
            echo json_encode($this->obj);
            exit;
        }
    }

    public function validState()
    {
        if(!in_array($this->decodedObj->state, DataLayer::getStates())) {
            $this->obj->error = true;
            $this->obj->message = 'Possible Spoofing: Submission includes a value that is not acceptable';
            echo json_encode($this->obj);
            exit;
        }
    }

    public function validMailing()
    {
        if($this->decodedObj->mailing != 1 && $this->decodedObj->mailing != 0) {
            $this->obj->error = true;
            $this->obj->message = 'Possible Spoofing: Submission includes a value that is not acceptable';
            echo json_encode($this->obj);
            exit;
        }
    }

    public function notBatman()
    {
        if($this->decodedObj->fname == "Batman") {
            $this->obj->error = true;
            $this->obj->message = "Sorry, you cannot be Batman.";
            echo json_encode($this->obj);
            exit;
        }else{
            $this->obj->message = "Cool name.";
        }
    }
}
