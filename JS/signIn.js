
const personalInfoSubmit = function (){

    let email = document.querySelector("#email").value;
    let password = document.querySelector("#password").value;

    //TODO:  do validation first

    let assocArray = {email: email, password: password};
    let JSONpayload = JSON.stringify(assocArray);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var fail = false;
            let responseObj = JSON.parse(this.responseText);
            if(responseObj.error  == true){
                document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">'+responseObj.message+'</span>';
            }else{
                document.querySelector("#submitFeedback").innerHTML = responseObj.message;
                document.location.href ="dashboard";
            }
        }else{
            document.querySelector("#submitFeedback").innerHTML = this.responseText;
        }
    };
    if(ValidateEmail(email)){
        xhttp.open("POST", "signIn", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("JSONpayload="+JSONpayload);
    }

}

function ValidateEmail(emailAddr) {
    var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.][a-zA-Z0-9-]{2,}$/;
    //var emailAddr = document.getElementById('email').value;
    console.log(emailAddr);
    if (emailAddr.match(validRegex)) {
        return true;
    } else {
        document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Invalid email address.</span>';
        return false;
    }
}

const submitButton = document.querySelector("#btnSignIn");
submitButton.addEventListener("click", ()=>{personalInfoSubmit()}, false);
