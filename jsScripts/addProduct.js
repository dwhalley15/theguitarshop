//Add event listeners to static buttons.
document.getElementById("nextBtn").addEventListener("click", nextTab);
document.getElementById("prevBtn").addEventListener("click", prevTab);
document.getElementById("onSale").addEventListener("click", onSale);
document.getElementById("submitBtn").addEventListener("click", lastValidation);

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

//A function to display the saleReduction input box if the onSale check box is check. 
//Also hides the saleReduction input box if the onSale check box is unchecked.
function onSale(){
  let onSale = document.forms["newProductForm"]["onSale"].checked;
  let rLabel = document.getElementById("saleRedLabel");
  let saleReduction = document.forms["newProductForm"]["saleReduction"];
  if(onSale == true){
    rLabel.style.display = "inline";
    saleReduction.style.display = "inline";
  }
  else{
    rLabel.style.display = "none";
    saleReduction.style.display = "none";
    document.forms["newProductForm"]["saleReduction"].value = 0;
  }
}

//A function to display or hide tabs based on the global variable currentTab.
//Also calls relevant validation functions and display or hide error functions.
function showTab(tab){
  
  let tabOne = document.getElementById("tabOne");
  let tabTwo = document.getElementById("tabTwo");
  let tabThree = document.getElementById("tabThree");
  let nextBtn = document.getElementById("nextBtn");
  let prevBtn = document.getElementById("prevBtn");
  let submitBtn = document.getElementById("submitBtn");
  let stepOne = document.getElementById("stepOne");
  let stepTwo = document.getElementById("stepTwo");
  let stepThree = document.getElementById("stepThree");
  
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
  else if(tab == 1 && descValidation() == true){
    onSale();
    tabOne.className = "hidden";
    tabTwo.className = "show";
    tabThree.className = "hidden";
    prevBtn.style.visibility = "visible";
    submitBtn.style.display = "none";
    nextBtn.style.display = "inline";
    stepOne.style.backgroundColor = "#bbbbbb";
    stepOne.style.opacity = "0.5";
    stepTwo.style.backgroundColor = "#607D3B";
    stepTwo.style.opacity = "1";
    stepThree.style.backgroundColor = "#bbbbbb";
    stepThree.style.opacity = "0.5";
  }
  else if(tab == 2 && priceValidation() == true){
    tabTwo.className = "hidden";
    tabThree.className = "show";
    nextBtn.style.display = "none";
    submitBtn.style.display = "inline";
    stepTwo.style.backgroundColor = "#bbbbbb";
    stepTwo.style.opacity = "0.5";
    stepThree.style.backgroundColor = "#607D3B";
    stepThree.style.opacity = "1";
  }
  else{
    prevTab();
    showError();
  }
}

//A function to validate the tabs input details of name, description and brand.
function descValidation(){
  let valid = true;
  let pName = document.forms["newProductForm"]["productName"].value;
  let pDesc = document.forms["newProductForm"]["productDesc"].value;
  let pBrand = document.forms["newProductForm"]["productBrand"].value;
  if(pName == "" || pDesc == "" || pBrand == ""){
    valid = false;
  }
  else if(pName.length > 255){
    valid = false;
    alert("Name is too long!");
    document.forms["newProductForm"]["productName"].value = "";
  }
  else if(pBrand.length > 255){
      valid = false;
      alert("Brand name is too long!");
      document.forms["newProductForm"]["productBrand"].value = "";
  }
  else if(pDesc.length > 1000){
    valid = false;
    alert("Description is too long!");
    document.forms["newProductForm"]["productDesc"].value = "";
  }
  return valid;
}

//A function to vavlidate the tabs input details of price and saleReduction.
function priceValidation(){
  let valid = true;
  let pPrice = document.forms["newProductForm"]["productPrice"].value;
  let onSale = document.forms["newProductForm"]["onSale"].checked;
  let saleReduction = document.forms["newProductForm"]["saleReduction"].value;
  let moneyFormat = /^\d+(\.\d{1,2})?$/;
  if(pPrice == 0 || onSale == true && saleReduction == 0){
    valid = false;
  }
  else if(!pPrice.match(moneyFormat)){
    alert("Price has too many decimal places");
    document.forms["newProductForm"]["productPrice"].value = 0;
    valid = false;
  }
  else if(onSale == true && !saleReduction.match(moneyFormat)){
    alert("Reduction amount has too many decimal places");
    document.forms["newProductForm"]["saleReduction"].value = 0;
    valid = false;
  }
  else if(pPrice.length > 255){
    valid = false;
    alert("Price is too long!");
    document.forms["newProductForm"]["productPrice"].value = 0;
  }
  else if(onSale == true && saleReduction.length > 255){
    valid = false;
    alert("Sale price is too long!");
    document.forms["newProductForm"]["saleReduction"].value = 0;
  }
  return valid;
}

//A function to validate the tabs details of sku, stock, type and image.
function lastValidation(){
  let pSKU = document.forms["newProductForm"]["productSKU"].value;
  let pStock = document.forms["newProductForm"]["productStock"].value;
  let pType = document.getElementById('productType').value;
  let pImage = document.getElementById('productImage');
  let iPath = pImage.value;
  const imageFileTypes = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

  if(pSKU == 0 || iPath == ""){
    showError();
  }
  else if(pType == "none"){
    alert("Product type not selected!");
  }
  else if(!imageFileTypes.exec(iPath)){
    alert('Please upload an image with an extension of either .jpeg/ .jpg/ .png/ .gif only.')
    pImage.value = "";
  }
  else if(pSKU.length > 255){
    valid = false;
    alert("SKU is too long!");
    document.forms["newProductForm"]["saleSKU"].value = 0;
  }
  else if(pStock.length > 255){
    valid = false;
    alert("Product stock is too long!");
    document.forms["newProductForm"]["saleStock"].value = 0;
  }
  else{
    document.forms["newProductForm"].submit();
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
