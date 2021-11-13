// grab the initial top offset of the navigation 
var stickyNavTop = 100;
            
// our function that decides weather the navigation bar should have "fixed" css position or not.
var stickyNav = function(){
    var scrollTop = $(window).scrollTop(); // our current vertical position from the top
            
    // if we've scrolled more than the navigation, change its position to fixed to stick to top,
    // otherwise change it back to relative
    console.log("sticky " + stickyNavTop + " scroll "+ scrollTop)
    if (scrollTop > stickyNavTop) { 
        $('nav').addClass('sticky slide-in-top');
        $('.nav-resp').addClass('sticky-mobile slide-in-top');
    } else {
        $('nav').removeClass('sticky slide-in-top'); 
        $('.nav-resp').removeClass('sticky-mobile slide-in-top'); 
    }
};

stickyNav();
// and run it again every time you scroll
$(window).scroll(function() {
    stickyNav();
});



var nav = document.getElementsByTagName("ul")[0];
var pos = document.getElementsByClassName("nav-resp")[0];

pos.innerHTML += nav.innerHTML;
var links = pos.querySelectorAll("a");

var dropdown = document.getElementsByClassName("homelink")[1];
var content = document.getElementsByClassName("dropdown-content")[1];
content.style.display="none"

var click = document.getElementsByClassName("fa fa-bars")[0];
click.addEventListener("click", appear);
dropdown.addEventListener("click", function(){
    if(content.style.display=="none"){
        content.style.display = "block";
    }
    else{
        content.style.display="none"
    }
});

var intervalId = window.setInterval(function(){
    if(window.innerWidth<1000){
        links.forEach(item => {
            item.addEventListener('click', event => {
                pos.style.display="none";
            })
            })
    }
    else{
        pos.style.display="none";
    }


    }, 500);

    function appear(){
    if(pos.style.display=="none"){
        pos.style.display = "block"
    }
    else{
        pos.style.display="none"
    }
}

