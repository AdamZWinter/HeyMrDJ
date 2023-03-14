
const personalInfoSubmit = function (){

    let fname = document.querySelector("#fname").value;
    let lname = document.querySelector("#lname").value;
    let email = document.querySelector("#email").value;
    let phone = document.querySelector("#phone").value;
    let state = document.querySelector("#state")[document.querySelector("#state").selectedIndex].innerHTML;
    let password = document.querySelector("#password").value;
    let password2 = document.querySelector("#password2").value;

    //TODO:  do validation first

    let assocArray = {fname: fname, lname: lname, email: email, phone: phone, state: state, password: password};
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
                document.location.href ="signIn";
            }
        }else{
            document.querySelector("#submitFeedback").innerHTML = this.responseText;
        }
    };
    if(ValidateEmail(email) && ValidatePhone(phone) && matchPasswords(password, password2)){
        xhttp.open("POST", "register", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("JSONpayload="+JSONpayload);
    }

}

function matchPasswords(password, password2) {
    if (password === password2) {
        return true;
    } else {
        document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Passwords do not match.</span>';
        return false;
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

function ValidatePhone(phoneNum) {
    var validRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    //var phoneNum = document.getElementById('phone').value;
    console.log(phoneNum);
    if (phoneNum.match(validRegex)) {
        return true;
    } else {
        document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Invalid phone number.</span>';
        return false;
    }
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

const submitButton = document.querySelector("#submitPersonalInfo");
submitButton.addEventListener("click", ()=>{personalInfoSubmit()}, false);
