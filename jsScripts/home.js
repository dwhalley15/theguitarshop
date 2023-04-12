//Add event listener to when the page loads.
document.addEventListener("DOMContentLoaded", function(){
  showSlides();
});

//Sets slideNo as a global variable.
let slideNo = 0;

//A function to change an image based on a timer. 
function showSlides(){
  let i;
  let slides = document.getElementsByClassName('slide');
  let dots = document.getElementsByClassName('slideDot');
  for (i =0; i < slides.length; i++){
    slides[i].style.display = "none";
  }
  slideNo++;
  if(slideNo > slides.length){
    slideNo = 1;
  }
  for(i = 0; i < dots.length; i++){
    dots[i].className = dots[i].className.replace(" active" ,"");
  }
  slides[slideNo-1].style.display = "block";
  dots[slideNo-1].className +=" active";
  setTimeout(showSlides, 5000);
}