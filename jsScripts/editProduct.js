//Add event listeners to static buttons.
document.getElementById("onSale").addEventListener("click", onSale); 
document.getElementById("editProductBtn").addEventListener("click", validate); 

//Add event listener to when the page loads runs a function to display the saleReduction input box if the onSale check box is check. 
document.addEventListener("DOMContentLoaded", function(){
  if(document.forms["editProduct"]["on_sale"].checked == true){
    let rLabel = document.getElementById("saleRedLabel");
    let saleReduction = document.forms["editProduct"]["saleReduction"];
    rLabel.style.display = "inline";
    saleReduction.style.display = "inline";
  }
});

//A function to display the saleReduction input box if the onSale check box is check. 
//Also hides the saleReduction input box if the onSale check box is unchecked.
function onSale(){
  let onSale = document.forms["editProduct"]["on_sale"].checked;
  let rLabel = document.getElementById("saleRedLabel");
  let saleReduction = document.forms["editProduct"]["saleReduction"];
  if(onSale == true){
    rLabel.style.display = "inline";
    saleReduction.style.display = "inline";
  }
  else{
    rLabel.style.display = "none";
    saleReduction.style.display = "none";
    document.forms["editProduct"]["saleReduction"].value = 0;
  }
}

//A function to validate all product input details before posting the editProduct form.
function validate(){
  let pName = document.forms["editProduct"]["name"].value;
  let pDesc = document.forms["editProduct"]["description"].value;
  let pBrand = document.forms["editProduct"]["brand"].value;
  let pPrice = document.forms["editProduct"]["price"].value;
  let onSale = document.forms["editProduct"]["on_sale"].checked;
  let saleReduction = document.forms["editProduct"]["saleReduction"].value;
  let pSKU = document.forms["editProduct"]["sku"].value;
  let pStock = document.forms["editProduct"]["stock"].value;
  let moneyFormat = /^\d+(\.\d{1,2})?$/;
  if(pName == "" || pDesc == "" || pBrand == "" || pPrice == 0 || pSKU == 0 || onSale == true && saleReduction == 0){
    showError();
  }
  else if(!pPrice.match(moneyFormat)){
    alert("Price has too many decimal places");
    document.forms["editProduct"]["price"].value = 0;
  }
  else if(onSale == true && !saleReduction.match(moneyFormat)){
    alert("Reduction amount has too many decimal places");
    document.forms["editProduct"]["saleReduction"].value = 0;
  }
  else{
    if(confirm("Are you sure you want to update this product?") == true){
      document.forms["editProduct"].submit();
    }
  }
}

//A function to hide all page error messages.
function showError(){
  let error = document.getElementsByClassName("error");
  for(let i = 0; i < error.length; i++){
      error[i].style.display = "inline";
  }
}