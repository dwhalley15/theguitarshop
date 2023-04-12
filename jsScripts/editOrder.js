//Add event listeners to static buttons.
document.getElementById("editAddBtn").addEventListener("click", validate);

//Add event listeners to dynamic buttons which display a confirmation window before posting deleteLine forms.
var btns = document.querySelectorAll('.deleteLineBtn');
btns.forEach(function(btn) {
  btn.addEventListener('click', function(){
    let id = "r" + event.target.id;
    if(confirm("Are you sure you want to remove this product from your order?") == true){
      document.forms[id].submit();
    }
  });
});

//A function to display all page error messages.
function showError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "inline";
  }
}

//A function to validate all input address section details.
function validate(){
  let sAdd = document.forms["editAddress"]["street_address"].value;
  let tAdd = document.forms["editAddress"]["town"].value;
  let cAdd = document.forms["editAddress"]["county"].value;
  let pAdd = document.forms["editAddress"]["post_code"].value;
  let nameFormat = /^[A-Za-z\s]+$/;
  var pCodeFormat = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
  if(sAdd == "" || tAdd == "" || cAdd == "" || pAdd == ""){
    showError();
  }
  else if(!tAdd.match(nameFormat) || !cAdd.match(nameFormat)){
    alert("You have entered an invalid address!");
    document.forms["editAddress"]["tAdd"].value = "";
    document.forms["editAddress"]["cAdd"].value = "";
  }
  else if(!pAdd.match(pCodeFormat)){
    alert("You have entered an invalid post code!");
    document.forms["editAddress"]["pCode"].value = "";
  }
  else{
    if(confirm("Are you sure you want to update your order?") == true){
      document.forms["editAddress"].submit();
    }
  }
}
