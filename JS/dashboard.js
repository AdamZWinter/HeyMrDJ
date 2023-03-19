
const addEvent = function (){
    //document.querySelector("#submitFeedback").innerHTML = "TEST";
    let name = document.querySelector("#name").value;
    let state = document.querySelector("#state")[document.querySelector("#state").selectedIndex].innerHTML;
    let date = document.querySelector("#date").value;
    console.log(date);
    let assocArray = {name: name, state: state, date: date};
    let JSONpayload = JSON.stringify(assocArray);

    $.post("event", {JSONpayload: JSONpayload}, function (response){
        //$("#submitFeedback").html(response);
        try {
            let responseObj = JSON.parse(response);
            if (responseObj.error == true) {
                console.log('Post success error true');
                document.querySelector("#submitFeedback").innerHTML = responseObj.message;
                //document.querySelector("#submitFeedback").innerHTML = "TRUE";
            } else {
                console.log('Post success error false');
                console.log(responseObj.message);
                document.querySelector("#submitFeedback").innerHTML = responseObj.message;
                //document.querySelector("#submitFeedback").innerHTML = "FALSE";
            }
        } catch (e) {
            //$("#submitFeedback").html(response);
            document.querySelector("#submitFeedback").innerHTML = "Caught Exception." + response;
        }
    })
        .done(function() {
            //alert( "second success" );
        })
        .fail(function(xhr, status, error) {
            document.querySelector("#submitFeedback").innerHTML = "Fail.";
            console.log("post fail");
            //console.log(xhr);
            console.log(status);
            console.log(error);
            console.log(xhr.responseText);
            //alert( "error" );
        })
        .always(function() {
            //alert( "finished" );
        });
}


const submitButton = document.querySelector("#btnAddEvent");
submitButton.addEventListener("click", ()=>{addEvent()}, false);