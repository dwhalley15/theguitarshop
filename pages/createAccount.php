<?php
  session_start();
  session_regenerate_id();
?>

<!DOCTYPE html>
<html>
  
  <?php 
    include 'head.php';
    include 'header.php';
  ?>
  
  <body>
    <div class="content" id="multiPageForm">
      <form action="newAccount.php" method="post" name="newAccountForm" id="newAccountForm"> 
        <h3>Account Sign up</h3>
        <div class="show" id="tabOne">   
          <fieldset class="form" name="newAccountName" id="newAccountName" form="newAccountForm">
            <legend>Name Details</legend>
            <input type="hidden" name="formID" value="<?php echo $_POST['formID']; ?>">
            <br>
            <label for="firstName">First Name:</label><span class="error">*</span>
            <input type="text" id="firstName" name="firstName" maxlength="255" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>" placeholder="First Name" required>
            <br>
            <label for="lastName">Last Name:</label><span class="error">*</span>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" maxlength="255" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName']; ?>" required>
            <br>
            <p class="error" id="formValidation">Fields marked with * must be completed!</p>
          </fieldset>
        </div>
        <div class="hidden" id="tabTwo">   
          <fieldset class="form" name="newAccountCont" id="newAccountCont" form="newAccountForm">
            <legend>Contact Details</legend>
              <input type="hidden" name="formID" value="<?php echo $_POST['formID']; ?>">
              <br>
              <label for="emailAdd">Email Address:</label><span class="error">*</span>
              <input type="email" id="emailAdd" name="emailAdd" placeholder="Email Address" maxlength="255" value="<?php if(isset($_POST['emailAdd'])) echo $_POST['emailAdd']; ?>" required>
              <br>
              <label for="pNum">Phone Number:</label><span class="error">*</span>
              <input type="tel" id="pNum" name="pNum" placeholder="Phone Number" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" maxlength="11" value="<?php if(isset($_POST['pNum'])) echo $_POST['pNum']; ?>" required>
              <br>
              <p class="error" id="formValidation">Fields marked with * must be completed!</p>
            </fieldset>
        </div>
        <div class="hidden" id="tabThree">
          <fieldset class="form" name="newAccountAdd" id="newAccountAdd" form="newAccountForm">
                <legend>Address Details</legend>
                <input type="hidden" name="formID" value="<?php echo $_POST['formID']; ?>">
                <br>
                <label for="sAdd">Street Address:</label><span class="error">*</span>
                <input type="text" id="sAdd" name="sAdd" placeholder="Street Address" maxlength="255" value="<?php if(isset($_POST['sAdd'])) echo $_POST['sAdd']; ?>" required>
                <br>
                <label for="tAdd">Town/City:</label><span class="error">*</span>
                <input type="text" id="tAdd" name="tAdd" placeholder="Town/City" maxlength="255" value="<?php if(isset($_POST['tAdd'])) echo $_POST['tAdd']; ?>" required>
                <br>
                <label for="cAdd">County:</label><span class="error">*</span>
                <input type="text" id="cAdd" name="cAdd" placeholder="County" maxlength="255" value="<?php if(isset($_POST['cAdd'])) echo $_POST['cAdd']; ?>" required>
                <br>
                <label for="pCode">Post Code:</label><span class="error">*</span>
                <input type="text" id="pCode" name="pCode" placeholder="Post Code" maxlength="9" pattern="[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? [0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}" value="<?php if(isset($_POST['pCode'])) echo $_POST['pCode']; ?>" required>
                <br>
                <p class="error" id="formValidation">Fields marked with * must be completed!</p>
              </fieldset>
          </div> 
          <div class="hidden" id="tabFour">
              <fieldset class="form" name="newAccountPass" id="newAccountPass" form="newAccountForm">
                <legend>Set Password</legend>
                <label for="passwordFirst">Password:</label><p class="error">*</p>
                <input type="password" id="passwordFirst" name="passwordFirst" placeholder="Password" maxlength="20" required>
                <br>
                <label for="passwordConfirm">Confirm Password:</label><p class="error">*</p>
                <input type="password" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm Password" maxlength="20" required>
                <br>
                <p class="error" id="formValidation">Fields marked with * must be completed!</p>
              </fieldset>
          </div>
          <div class="btns">
              <input type="button" id="prevBtn" name="prevBtn" class="confirmBtn" value="Previous">
              <input type="button" id="nextBtn" name="nextBtn" class="confirmBtn" value="Next">
              <input type="button" id="submitBtn" name="submitBtn" class="confirmBtn" value="Submit">
          </div>
      </form>
      <div style="text-align:center;margin-top:40px;"> 
        <span class="step" id="stepOne"></span>
        <span class="step" id="stepTwo"></span>
        <span class="step" id="stepThree"></span>
        <span class="step" id="stepFour"></span> 
      </div>  
      <p class="login-text">Already have an account? <a class="linkBtn" href="login.php">Login</a></p>  
    </div>
    <script src="../jsScripts/createAccount.js"></script>
  </body>
  
    <?php include 'footer.php'?>

</html>