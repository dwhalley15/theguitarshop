//Add event listeners to static buttons.
document.getElementById("nextBtn").addEventListener("click", nextTab);
document.getElementById("prevBtn").addEventListener("click", prevTab);
document.getElementById("submitBtn").addEventListener("click", passValidation);

//Set currentTab as a global variable.
var currentTab = 0;

//A function to increase currentTab and run showTab function.
function nextTab(){
    currentTab = currentTab + 1;
    showTab(currentTab);
}

//A function to decrease currentTab and run ShowTab function.
function prevTab(){
  currentTab = currentTab - 1;
  showTab(currentTab);
}

//A function to display or hide tabs based on the global variable currentTab.
//Also calls relevant validation functions and display or hide error functions.
function showTab(tab){
  
  let tabOne = document.getElementById("tabOne");
  let tabTwo = document.getElementById("tabTwo");
  let tabThree = document.getElementById("tabThree");
  let tabFour = document.getElementById("tabFour");
  let nextBtn = document.getElementById("nextBtn");
  let prevBtn = document.getElementById("prevBtn");
  let submitBtn = document.getElementById("submitBtn");
  let stepOne = document.getElementById("stepOne");
  let stepTwo = document.getElementById("stepTwo");
  let stepThree = document.getElementById("stepThree");
  let stepFour = document.getElementById("stepFour");
  
  clearError();
  
  if(tab == 0){
    tabOne.className = "show";
    tabTwo.className = "hidden";
    prevBtn.style.visibility  = "hidden";
    stepOne.style.backgroundColor = "#607D3B";
    stepOne.style.opacity = "1";
    stepTwo.style.backgroundColor = "#bbbbbb";
    stepTwo.style.opacity = "0.5";
  }
  else if(tab == 1 && nameValidation() == true){
    tabOne.className = "hidden";
    tabTwo.className = "show";
    tabThree.className = "hidden";
    prevBtn.style.visibility = "visible";
    stepOne.style.backgroundColor = "#bbbbbb";
    stepOne.style.opacity = "0.5";
    stepTwo.style.backgroundColor = "#607D3B";
    stepTwo.style.opacity = "1";
    stepThree.style.backgroundColor = "#bbbbbb";
    stepThree.style.opacity = "0.5";
  }
  else if(tab == 2 && contactValidation() == true){
    tabTwo.className = "hidden";
    tabThree.className = "show";
    tabFour.className = "hidden";
    nextBtn.style.display = "inline";
    submitBtn.style.display = "none";
    stepTwo.style.backgroundColor = "#bbbbbb";
    stepTwo.style.opacity = "0.5";
    stepThree.style.backgroundColor = "#607D3B";
    stepThree.style.opacity = "1";
    stepFour.style.backgroundColor = "#bbbbbb";
    stepFour.style.opacity = "0.5";
  }
  else if(tab == 3 && addValidation() == true){
    tabThree.className = "hidden";
    tabFour.className = "show";
    nextBtn.style.display = "none";
    submitBtn.style.display = "inline";
    stepThree.style.backgroundColor = "#bbbbbb";
    stepThree.style.opacity = "0.5";
    stepFour.style.backgroundColor = "#607D3B";
    stepFour.style.opacity = "1";
  }
  else{
    prevTab();
    showError();
  }
}

//A function to validate the tabs input details of first name and last name.
function nameValidation(){
  let valid = true;
  let firstName = document.forms["newAccountForm"]["firstName"].value;
  let lastName = document.forms["newAccountForm"]["lastName"].value;
  let nameFormat = /^[A-Za-z\s]+$/;
  if(firstName == "" || lastName == ""){
      valid = false;
  }
  else if(!firstName.match(nameFormat) || !lastName.match(nameFormat)){
    alert("You have entered an invalid name!");
    document.forms["newAccountForm"]["firstName"].value = "";
    document.forms["newAccountForm"]["lastName"].value = "";
    valid = false;
  }
  else if(firstName.length > 255){
    valid = false;
    alert("Name is too long!");
    document.forms["newAccountForm"]["firstName"].value = "";
  }
  else if(lastName.length > 255){
    valid = false;
    alert("Name is too long!");
    document.forms["newAccountForm"]["lastName"].value = "";
  }
  return valid;
}

//A function to validate the tabs input details of email address and phone number.
function contactValidation(){
  let valid = true;
  let email = document.forms["newAccountForm"]["emailAdd"].value;
  let pNumb = document.forms["newAccountForm"]["pNum"].value;
  var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  var phoneFormat = /^\d{11}$/;
  if(email == "" || pNumb == ""){
      valid = false;
  }
  else if(!email.match(mailFormat)){
    alert("You have entered an invalid email address!");
    document.forms["newAccountForm"]["emailAdd"].value = "";
    valid = false;
  }
  else if(email.length > 255){
    alert("You have entered an invalid email address!");
    document.forms["newAccountForm"]["emailAdd"].value = "";
    valid = false;
  } 
  else if(!pNumb.match(phoneFormat)){
    alert("You have entered an invalid phone number!");
    document.forms["newAccountForm"]["pNum"].value = "";
    valid = false;
  }
  return valid;
}

//A function to validate the tabs input details of all address sections.
function addValidation(){
  let valid = true;
  let sAdd = document.forms["newAccountForm"]["sAdd"].value;
  let tAdd = document.forms["newAccountForm"]["tAdd"].value;
  let cAdd = document.forms["newAccountForm"]["cAdd"].value;
  let pCode = document.forms["newAccountForm"]["pCode"].value;
  let nameFormat = /^[A-Za-z\s]+$/;
  var pCodeFormat = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
  if(sAdd == "" || tAdd == "" || cAdd == "" || pCode == ""){
      valid = false;
  }
  else if(!pCode.match(pCodeFormat)){
    alert("You have entered an invalid post code!");
    document.forms["newAccountForm"]["pCode"].value = "";
    valid = false;
  }
  else if(!tAdd.match(nameFormat) || !cAdd.match(nameFormat)){
    alert("You have entered an invalid address!");
    document.forms["newAccountForm"]["tAdd"].value = "";
    document.forms["newAccountForm"]["cAdd"].value = "";
    valid = false;
  }
  else if(sAdd.length > 255){
    valid = false;
    alert("Street Address is too long!");
    document.forms["newAccountForm"]["sAdd"].value = "";
  }
  else if(tAdd.length > 255){
    valid = false;
    alert("Town is too long!");
    document.forms["newAccountForm"]["tAdd"].value = "";
  }
  else if(cAdd.length > 255){
    valid = false;
    alert("County is too long!");
    document.forms["newAccountForm"]["cAdd"].value = "";
  }
  return valid;
}

//A function to validate the tabs input details of password and confirm password.
function passValidation(){
  let pass = document.forms["newAccountForm"]["passwordFirst"].value;
  let cPass = document.forms["newAccountForm"]["passwordConfirm"].value;
  var passFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
  if(pass == "" || cPass == ""){
    showError();
  }
  else if(pass != cPass){
    alert("Your passwords do not match!");
    document.forms["newAccountForm"]["passwordConfirm"].value = "";
  }
  else if(!pass.match(passFormat)){
    alert("Your password must have 8 characters, have upper and lower case letters and a number!");
    document.forms["newAccountForm"]["passwordFirst"].value = "";
    document.forms["newAccountForm"]["passwordConfirm"].value = "";
  }
  else{
    document.forms["newAccountForm"].submit();
  }
}

//A function to display all page error messages.
function showError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "inline";
  }
}

//A function to hide all page error messages.
function clearError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "none";
  }
}

