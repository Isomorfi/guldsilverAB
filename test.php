<?php
include("db_connection.php");




?>


<Head>      
    <Title>     
    Add the border using internal CSS  
    </Title>  
    <style type = "text/css">  
        body {
         box-sizing: border-box;
         margin: 0;
         background-color: white;
         }
         
         header {
           background-color: powderblue;
           height:100px;
         }
         
         .topnav a {
         float: left;
         color: black;
         text-align: center;
         text-decoration: none;
         padding: 10px 10px;
         }
            
         #topnav-right {
         float: right;    
         }
         .a {
    display: inline-block;
    position: relative;
    margin: 1%;

    width: 200px;
    height: 200px;
    padding: 10px 10px;

    }
    .b {
    display: inline-block;
    position: relative;
    margin: 1%;

    width: 200px;
    height: 200px;
    padding: 10px 10px;

    }
    .c {
    display: inline-block;
    position: relative;
    margin: 1%;

    width: 200px;
    height: 200px;
    padding: 10px 10px;

    }
    .d {
    display: inline-block;
    position: relative;
    margin: 1%;

    width: 200px;
    height: 200px;
    padding: 0 10px;

    }
      
    </style>  
    </Head>  
    
    <BODY>
        
        <header>

                <center><label>&#10004; Snabb leverans  &#10004; Låga priser  &#10004; Miljöcertifierade produkter</label></center>
                <div class="topnav">

                   <a href="#">
                       <h1>Sverige-mineralen AB</h1>
                   </a>

                   <div id="topnav-right">
                      <a href="#">
                         <h2>Logga in</h2>
                      </a>
                      <a href="#">
                         <h2>Skapa konto</h2>
                      </a>
                       <a href="#">
                         <h2>Om oss</h2>
                      </a>
                      <a href="#">
                         <h2>Kontakta oss</h2>
                      </a>
                   </div>
                </div>

      </header>
        


    <center><h1>Välkommen till Sveriges minsta e-handelsbutik för mineraler.</h1></center>
        <br>
        <br>
        



<div style="width:650px; margin:0 auto;">

    <div style="width:300px; float:left;">
        <p><label for="fname"><br><br>Våra huvudprodukter, guld och silver, har en renhet på 99,9% och utvinns i våra egna gruvor på Tallvik i Överkalix i norr respektive Kolsva i söder. Att produkterna utvinns i Sverige och har en så pass hög halt av guld och silver i kombination med vårat låga pris, som är 50% under marknadsvärde, gör våra produkter helt unika. <br><br> För att ta del av våra erbjudanden krävs det att du registrerar ett konto hos oss.</label></p>
    </div>

    <div style="width:300px; float:right;">
        <p><img src="https://packbud.com/sites/packbud/files/field/gmapimagei154428739gmapimage106949.png" alt="Logo" width="350" height="300"></p>

    </div>


</div>
<div style="clear: both;"></div>







<br>
<br>
<p style="text-align:center;"><label for="fname"><b>Några av våra produkter:</b></label></p>
        





    <center>
        <div class="a"><img src="http://www.thejewellerytube.com/wp-content/uploads/2020/08/fine-gold-bricks-the-jewellery-tube.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Guld</label></div>
<div class="b"><img src="https://www.valutahandel.se/wp-content/uploads/silver-tackor.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Silver</label></div>
<div class="c"><img src="https://agmetalminer.com/mmwp/wp-content/uploads/2021/01/ShawnHempel_AdobeStock_copperbars_012621.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Koppar</label></div>
<div class="d"><img src="https://media.istockphoto.com/photos/diamond-texture-closeup-and-kaleidoscope-top-view-of-round-gemstone-picture-id990183542?k=20&m=990183542&s=612x612&w=0&h=Um2NiUZOuj6UyABQ-vshECNoshXYqQZs_y9GWZs6dQc=" alt="Logo" width="200" height="200"><br><br>
        <label>Diamant</label></div>
    </center>
<center>
    <br><br><br>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></center>
    </BODY>