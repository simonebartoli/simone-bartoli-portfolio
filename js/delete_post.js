var post = document.getElementsByClassName("post")[0];
var delete_button = document.getElementById("delete-post");

delete_button.addEventListener("click", function(){
    if(confirm("Are you sure you want to delete this post?")){
        var link = "/php/delete_post.php?";
        var info = post.getElementsByClassName("perinf")[0].innerHTML;
        var date = post.getElementsByClassName("date")[0].innerHTML;
        link = link + "post=true&name=" + info + "&date=" + date;
        window.open(link, '_self');
    }
})


var reply = document.querySelectorAll(".replies");
reply.forEach(item => {
    item.getElementsByClassName("delete-reply")[0].addEventListener("click", function(){
        if(confirm("Are you sure you want to delete this reply?")){
            var link = "/php/delete_post.php?";
            var info = item.getElementsByClassName("perinf")[0].innerHTML;
            var date = item.getElementsByClassName("date")[0].innerHTML;
            link = link + "post=false&name=" + info + "&date=" + date;
            window.open(link, '_self');
        }
    })
})
