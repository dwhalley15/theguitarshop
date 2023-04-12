//Add event listeners to static buttons.
document.getElementById("confirmBtn").addEventListener("click", validate);

//A function to display all page error messages.
function showError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "inline";
  }
}

//A function to validate the address before posting new_order form.
function validate(){
  let sAdd = document.forms["new_order"]["street_address"].value;
  let tAdd = document.forms["new_order"]["town"].value;
  let cAdd = document.forms["new_order"]["county"].value;
  let pAdd = document.forms["new_order"]["post_code"].value;
  let nameFormat = /^[A-Za-z\s]+$/;
  var pCodeFormat = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
  if(sAdd == "" || tAdd == "" || cAdd == "" || pAdd == ""){
    showError();
  }
  else if(!tAdd.match(nameFormat) || !cAdd.match(nameFormat)){
    alert("You have entered an invalid address!");
    document.forms["new_order"]["tAdd"].value = "";
    document.forms["new_order"]["cAdd"].value = "";
  }
  else if(!pAdd.match(pCodeFormat)){
    alert("You have entered an invalid post code!");
    document.forms["new_order"]["pCode"].value = "";
  }
  else{
    document.forms["new_order"].submit();
  }
}