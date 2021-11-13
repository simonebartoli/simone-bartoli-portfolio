function main(){
    var title = document.getElementById("title");
    var message = document.getElementById("message");
    var form_post = document.getElementById("postblog");

    var addToggle = document.getElementsByClassName("add-toggle")[0];
    var form_container = document.getElementsByClassName("post-form")[0];

    form_post.addEventListener("submit", function(e){
        if(title.value === "" || title.value.length>35 || title.value.length <5){
            e.preventDefault();
            title.style.boxShadow = "0 0 10px red";
        }
        else{
            title.style.boxShadow = "unset";
        }
        if(message.value === "" || message.value.length>500 || message.value.length <20){
            e.preventDefault();
            message.style.boxShadow = "0 0 10px red";
        }
        else{
            message.style.boxShadow = "unset";
        }
    })


    var addButton = document.getElementById("addPost");
    addButton.addEventListener("click", function(){
        addToggle.style.display="none";
        form_container.style.display="block";
        document.getElementsByClassName("more")[0].style.justifyContent = "center";
    })

    
    var cancelMessage = document.getElementById("cancel");
    cancelMessage.addEventListener("click", function(){
        if(confirm("Are you sure you want to come back?\n(YOUR MESSAGE WILL BE DELETED)")){
            title.style.boxShadow = "unset";
            message.style.boxShadow = "unset";
            title.value = "";
            message.value = "";
            addToggle.style.display="flex";
            form_container.style.display="none";
            document.getElementsByClassName("more")[0].style.justifyContent = "flex-start";
        }
    })
    

    var resetMessage = document.getElementById("reset");
    resetMessage.addEventListener("click", function(e){
        e.preventDefault();
        if(confirm("Are you sure you want to reset?")){
            title.style.boxShadow = "unset";
            message.style.boxShadow = "unset";
            title.value = "";
            message.value = "";
        }
    })

    title.addEventListener("keypress", function(){
        if(title.value.length>=35){
            alert("Your title is too LONG");
        }
    })
    message.addEventListener("keypress", function(){
        if(message.value.length>=400){
            alert("Your message is too LONG");
        }
    })
}
main();