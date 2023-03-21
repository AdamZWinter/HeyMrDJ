const updateSettings = function (){
    //document.querySelector("#submitFeedback").innerHTML = "TEST";
    let djname = document.querySelector("#djname").value;
    let bio = document.querySelector("#bio").value;
    console.log(bio);
    let assocArray = {djname: djname, bio: bio};
    let JSONpayload = JSON.stringify(assocArray);

    $.post("dashboard/settings", {JSONpayload: JSONpayload}, function (response){
        //$("#submitFeedback").html(response);
        console.log('Response: ' + response);
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
            document.querySelector("#submitFeedback").innerHTML = "Caught Exception: "+e +"   Response: "+  + response;
        }
    })
        .done(function() {
            //alert( "second success" );
            //google.charts.setOnLoadCallback(getEvents);
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


const submitButton = document.querySelector("#btnUpdateSettings");
submitButton.addEventListener("click", ()=>{updateSettings()}, false);

