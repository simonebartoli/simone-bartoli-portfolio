function main(){
    var email = document.getElementById("login_email");
    var pass = document.getElementById("pass_email");
    var form = document.getElementsByTagName("form")[0];
    
    //var mistakes = new Array();
    var error = false;
    
    form.addEventListener("submit", (e) =>{
        if(email.value==="" || email.value.length>50 || email.value.length<10){
            email.style.boxShadow = "0 0 10px red";
            error = true;
        }
        else{
            email.style.boxShadow = "unset";
        }
        if(pass.value==="" || pass.value.length>64 || pass.value.length<4){
            pass.style.boxShadow = "0 0 10px red";
            error = true;
        }
        else{
            pass.style.boxShadow = "unset";
        }
        
        if(error){
            error=false;
            e.preventDefault();
        }
        
    });
}

main();
