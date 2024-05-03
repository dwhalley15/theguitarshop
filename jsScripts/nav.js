//A Function for the drop down menu
document.addEventListener("DOMContentLoaded", function() {
  const dropdownButton = document.querySelector('.dropdownBtn');
  const dropdown = document.querySelector('.dropdown ');

  dropdownButton.addEventListener('click', function() {
    dropdown.classList.toggle('active');
  });

  // Close the dropdown when clicking outside of it
  window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdownBtn')) {
      const dropdowns = document.querySelectorAll('.dropdown');
      dropdowns.forEach(function(dropdown) {
        if (dropdown.classList.contains('active')) {
          dropdown.classList.remove('active');
        }
      });
    }
  });
});