//A condition that stops form resubmission.
if(window.history.replaceState){
  window.history.replaceState(null, null, window.location.href);
}