//Add event listeners to static buttons.
document.getElementById("ordersBtn").addEventListener("click", ordersTab);
document.getElementById("detailsBtn").addEventListener("click", detailsTab);
document.getElementById("optionsBtn").addEventListener("click", optionsTab);
document.getElementById("updateDetails").addEventListener("click", validateUpdate);
document.getElementById("deleteAccount").addEventListener("click", deleteAccount);

//Add event listeners to dynamic buttons which display a confirmation window before initialising deleteOrder script.
var btns = document.querySelectorAll('.deleteOrderBtn');
btns.forEach(function(btn) {
  btn.addEventListener('click', function(){
    let id = event.target.id;
    if(confirm("Are you sure you want to cancel this order?") == true){
      window.location.href="../phpScripts/deleteOrder.php?order_id="+id;
    }
  });
});

//A function to switch to order display.
function ordersTab(){
  document.getElementById('ordersTab').className = "show";
  document.getElementById('detailsTab').className = "hidden";
  document.getElementById('optionsTab').className = "hidden";
}

//A function to switch to user details display.
function detailsTab(){
  document.getElementById('ordersTab').className = "hidden";
  document.getElementById('detailsTab').className = "show";
  document.getElementById('optionsTab').className = "hidden";
}

//A function to switch to options display.
function optionsTab(){
  document.getElementById('ordersTab').className = "hidden";
  document.getElementById('detailsTab').className = "hidden";
  document.getElementById('optionsTab').className = "show";
}

//A function to validate user details before posting updateAccount form.
function validateUpdate(){
  let firstName = document.forms["updateAccount"]["first_name"].value;
  let lastName = document.forms["updateAccount"]["last_name"].value;
  let phoneNumber = document.forms["updateAccount"]["phone_number"].value;
  let sAdd = document.forms["updateAccount"]["street_address"].value;
  let tAdd = document.forms["updateAccount"]["town"].value;
  let cAdd = document.forms["updateAccount"]["county"].value;
  let pCode = document.forms["updateAccount"]["post_code"].value;
  var nameFormat = /^[A-Za-z\s]+$/;
  var pCodeFormat = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
  var phoneFormat = /^\d{11}$/;
  if(firstName == "" || lastName == "" || phoneNumber == "" || sAdd == "" || tAdd == "" || cAdd == "" || pCode == ""){
    showError();
  }
  else if(!firstName.match(nameFormat) || !lastName.match(nameFormat)){
    alert("You have entered an invalid name");
    document.forms["updateAccount"]["first_name"].value = "";
    document.forms["updateAccount"]["last_name"].value = "";
  }
  else if(!tAdd.match(nameFormat) || !cAdd.match(nameFormat)){
    alert("You have entered an invalid address");
    document.forms["updateAccount"]["town"].value = "";
    document.forms["updateAccount"]["county"].value = "";
  }
  else if(!phoneNumber.match(phoneFormat)){
    alert("You have entered an invalid phone number");
    document.forms["updateAccount"]["phone_number"].value = "";
  }
  else if(!pCode.match(pCodeFormat)){
    alert("You have entered an invalid post code");
    document.forms["updateAccount"]["post_code"].value = "";
  }
  else{
    if(confirm("Are you sure you want to update your details?") == true){
      document.forms["updateAccount"].submit();
    }
  }
}

//A function to display a confrimation window before posting deleteAccount form.
function deleteAccount(){
  if(confirm("Are you sure you want to delete your account?") == true){
      document.forms["deleteAccount"].submit();
    }
}

//A function to display all page error messages.
function showError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "inline";
  }
}