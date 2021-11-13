function main(){
    var message = document.getElementById("message");
    var form_post = document.getElementsByTagName("form")[0];

    form_post.addEventListener("submit", function(e){
        if(message.value === "" || message.value.length>500 || message.value.length <20){
            e.preventDefault();
            message.style.boxShadow = "0 0 10px red";
        }
        else{
            message.style.boxShadow = "unset";
        }
    })


    var addButton = document.getElementById("add-post");
    addButton.addEventListener("click", function(){
        form_post.style.display="flex";
    })

    
    var cancelMessage = document.getElementById("cancel");
    cancelMessage.addEventListener("click", function(){
        if(confirm("Are you sure you want to come back?\n(YOUR MESSAGE WILL BE DELETED)")){
            message.style.boxShadow = "unset";
            message.value = "";
            form_post.style.display="none";
        }
    })
    

    var resetMessage = document.getElementById("reset");
    resetMessage.addEventListener("click", function(e){
        e.preventDefault();
        if(confirm("Are you sure you want to reset?")){
            message.style.boxShadow = "unset";
            message.value = "";
        }
    })

    message.addEventListener("keypress", function(){
        if(message.value.length>=400){
            alert("Your message is too LONG");
        }
    })
}
main();