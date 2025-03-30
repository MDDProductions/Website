// Slideshow Functionality
let slideIndex = 0;

// Show slides for each section
showSlides("buying-slide");
showSlides("renting-slide");
showSlides("selling-slide");

function showSlides(slideId) {
  let slides = document.getElementById(slideId).getElementsByClassName("slide");
  
  // Loop through all slides and hide them
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  
  // Show the next slide
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  
  slides[slideIndex - 1].style.display = "block";
  setTimeout(function() { showSlides(slideId); }, 3000); // Change slide every 3 seconds
}
