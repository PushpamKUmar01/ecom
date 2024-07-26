//-------------------promotional---------------------

let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  slides[slideIndex-1].style.display = "block";  
  setTimeout(showSlides, 3000); // Change image every 2 seconds
}

function plusSlides(n) {
  showSlides(slideIndex += n);
}


   
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-button');
    const navUl = document.getElementById('nav-ul');
    
    toggleButton.addEventListener('click', function() {
        navUl.classList.toggle('show');
    });
});


