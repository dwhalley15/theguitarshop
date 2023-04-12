     <div class="top"> 
      <div class="banner">
        <div class="dropdown">
          <img class="dropdownBtn" src="../images/barsicon.png" alt="menu" style="width:30px;, height:30px;">
          <div class= "dropdownContent">
            <a href="productListing.php?type=electric">Electric</a>
            <a href="productListing.php?type=acoustic">Acoustic</a>
            <a href="productListing.php?type=amplifier">Amplifiers</a>
            <a href="productListing.php?type=strings">Strings</a>
            <a href="productListing.php?type=part">Parts</a>
            <a href="productListing.php?type=accessory">Accessories</a>
            <a href="login.php">Account</a>
          </div>
        </div>
        <a class="searchIcon" href="search.php"><img id="searchSubmit" name="searchSubmit" src="../images/searchicon.png" alt="search" style="width:30px;, height:30px;"></a>
        <div class="search">
          <form class="searchBar" name="searchBar" action="search.php" method="post">
            <input type="text" id="searchInput" name="search" placeholder="Search...">
            <input type="image" src="../images/searchicon.png" id="submitFull" alt="Search" name="searchSubmit" width="30" height="30">
          </form>
        </div>
        <h1 class="shopName"><a class='shopLogoLink' href='home.php'>The Guitar Shop<img id="bannerGuitar" src="../images/guitar.png" alt="menu" style="width:50px;, height:50px;"></a></h1>
        <a href="cart.php"><img id="checkoutIcon" src="../images/checkouticon.png" alt="checkout" style="width:30px;, height:30px;"> <p class='cartFloat'><?php if(!empty($_SESSION['cart'])) echo count($_SESSION['cart']); ?></p></a>
        <a href="login.php"> <img id="accountIcon" src="../images/accounticon.png" alt="account" style="width:30px;, height:30px;"/></a>
      </div>
      <div class="nav">
        <a href="productListing.php?type=electric">Electric</a>
        <a href="productListing.php?type=acoustic">Acoustic</a>
        <a href="productListing.php?type=amplifier">Amplifiers</a>
        <a href="productListing.php?type=strings">Strings</a>
        <a href="productListing.php?type=part">Parts</a>
        <a href="productListing.php?type=accessory">Accessories</a>
      </div>
    </div>


   