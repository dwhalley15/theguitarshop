//Add event listeners to static buttons.
document.getElementById("editImageBtn").addEventListener("click", imageCheck);

//A function to validate the image input details.
function imageCheck(){
  let pImage = document.getElementById('image');
  let iPath = pImage.value;
  const imageFileTypes = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
  if(!imageFileTypes.exec(iPath)){
    alert('Please upload an image with an extension of either .jpeg/ .jpg/ .png/ .gif only.')
    pImage.value = "";
  }
  else{
    if(confirm("Are you sure you want to update this product image?") == true){
      document.forms["editImageForm"].submit();
    }
  }
}