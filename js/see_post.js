function main(){
    var box = document.querySelectorAll(".post");
    var filter = document.getElementById("filter");
    box.forEach(item => {
        item.addEventListener("click", function(){
            var postid = item.getElementsByClassName("postID")[0].innerHTML;
            var link = "/view-post.php?" + "postid=" + postid;
            window.open(link);
        })
    })

    filter.addEventListener("change", function(e){
        document.getElementById("filter-form").submit();
        if(this.value == ""){
            window.open("/blog.php", "_self");
        }  
    })

}
main();