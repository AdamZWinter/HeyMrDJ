<?php
class PostedObj
{
    protected $_decodedObj;  //The object sent from the client decoded
    protected $_responseObj;         //The object that will be json encoded and returned to the client.
    function __construct($JSONpayload, $responseObj)
    {
        $this->_decodedObj = json_decode($JSONpayload);
        $this->_responseObj = $responseObj;
    }

    function getJSONencoded()
    {
        return json_encode($this->_decodedObj);
    }

    function getDecodedObject()
    {
        return $this->_decodedObj;
    }

    function getResponseObj()
    {
        return $this->_responseObj;
    }

    public function validName($generic = null)
    {
        $this->_decodedObj->fname = filter_var($this->_decodedObj->fname, FILTER_SANITIZE_STRING);
        $this->_decodedObj->lname = filter_var($this->_decodedObj->lname, FILTER_SANITIZE_STRING);
        if(empty($this->_decodedObj->fname) || empty($this->_decodedObj->lname)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "First and Last names are required";
            echo json_encode($this->_responseObj);
            exit;
        }
        if(!ctype_alpha($this->_decodedObj->fname) || !ctype_alpha($this->_decodedObj->lname)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Names can only contain letters";
            echo json_encode($this->_responseObj);
            exit;
        }
    }//end function validName

    public function validEmail()
    {
        if(empty($this->_decodedObj->email)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Email address is required.";
            echo json_encode($this->_responseObj);
            exit;
        }
        if(!filter_var($this->_decodedObj->email, FILTER_VALIDATE_EMAIL)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Invalid email address.";
            echo json_encode($this->_responseObj);
            exit;
        }
        $this->_responseObj->validEmail = true;
        return $this->_decodedObj->email;
    }

    public function validPhone()
    {
        if(empty($this->_decodedObj->phone)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Phone number is required.";
            echo json_encode($this->_responseObj);
            exit;
        }
        $this->_decodedObj->phone = filter_var($this->_decodedObj->phone, FILTER_SANITIZE_STRING);
        if(!preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/", $this->_decodedObj->phone)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Invalid phone number format.";
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    public function validState()
    {
        $this->_decodedObj->state = filter_var($this->_decodedObj->state, FILTER_SANITIZE_STRING);
        if(!in_array($this->_decodedObj->state, DataLayer::getStates())) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = 'Possible Spoofing: Submission includes a value that is not acceptable';
            echo json_encode($this->_responseObj);
            exit;
        }
    }

    public function notBatman()
    {
        if($this->_decodedObj->fname == "Batman") {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Sorry, you cannot be Batman.";
            echo json_encode($this->_responseObj);
            exit;
        }else{
            $this->_responseObj->message[] = "Cool name.";
        }
    }

    public function validNameGeneric()
    {
        $this->_decodedObj->name = filter_var($this->_decodedObj->name, FILTER_SANITIZE_STRING);
        if(empty($this->_decodedObj->name)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Name is required";
            echo json_encode($this->_responseObj);
            exit;
        }
        if(!preg_match("/^[a-zA-Z0-9 _.-]*$/", $this->_decodedObj->name)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Name can only contain letters, numbers, _, ., or -";
            echo json_encode($this->_responseObj);
            exit;
        }
        $this->_responseObj->message[] = 'Name is valid';
    }//end function validName

    public function validDate()
    {
        $this->_decodedObj->date = filter_var($this->_decodedObj->date, FILTER_SANITIZE_STRING);
        if(empty($this->_decodedObj->date)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Date is required";
            echo json_encode($this->_responseObj);
            exit;
        }
        if(!preg_match("/^\d\d\d\d-\d\d-\d\d$/", $this->_decodedObj->date)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "Invalid date.";
            echo json_encode($this->_responseObj);
            exit;
        }
        $this->_responseObj->message[] = 'Date is valid';
    }//end function validName

    /**
     * 
     * Sanitizes the provided field of the decoded JSON object $_decodedObj
     *
     * @param  $field String The field of the decoded JSON object to sanitize
     * @return void
     */
    public function sanitize($field)
    {
        $this->_decodedObj->$field = filter_var($this->_decodedObj->$field, FILTER_SANITIZE_STRING);
    }

    /**
     * 
     * Validates and sanitizes the provided field of the decoded JSON object $_decodedObj
     *
     * @param  $field String The field of the decoded JSON object to sanitize and validate
     * @return void
     */
    public function validNameByField($field)
    {
        $this->_decodedObj->$field = filter_var($this->_decodedObj->$field, FILTER_SANITIZE_STRING);
        if(!preg_match("/^[a-zA-Z0-9 _.-]*$/", $this->_decodedObj->$field)) {
            $this->_responseObj->error = true;
            $this->_responseObj->message[] = "$field can only contain letters, numbers, _, ., or -";
            echo json_encode($this->_responseObj);
            exit;
        }
        $value = $this->_decodedObj->$field;
        $this->_responseObj->message[] = "$field ($value) is valid";
    }//end function validName
}
