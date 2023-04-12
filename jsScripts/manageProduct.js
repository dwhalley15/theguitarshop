//Add event listeners to dynamic buttons which display a confirmation window before initialising deleteProduct script.
var btns = document.querySelectorAll('.deleteProdBtn');
btns.forEach(function(btn) {
  btn.addEventListener('click', function(){
    let id = event.target.id;
    if(confirm("Are you sure you want to Delete this product?") == true){
      window.location.href="../phpScripts/deleteProduct.php?prod_id="+id;
    }
  });
});
