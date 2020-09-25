<?php
  function info_request(){
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://trace.moe/api/me");
    
    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // mengembalikan hasil curl
    return $output;
  }

  $user = info_request();

  // ubah string JSON menjadi array
  $user = json_decode($user, TRUE);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />

  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">

  <title>ANIFIND - Easy Search</title>

  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/coming-sssoon.css" rel="stylesheet" />

  <!--     Fonts     -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">

</head>

<body>
  <nav class="navbar navbar-transparent navbar-fixed-top" role="navigation">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="#">
              <i class="fa fa-user"></i>
              Ivan Ongko .S / 10118137
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-envelope-o"></i>
              ivan.10118137@koding2an.web.id
            </a>
          </li>
        </ul>

      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav>
  <div class="main" style="background-image: url('images/megumin.png')">

    <!--    Change the image source '/images/default.jpg' with your favourite image.     -->

    <div class="cover black" data-color="black"></div>

    <!--   You can change the black color for the filter with those colors: blue, green, red, orange       -->

    <div class="container">
      <h1 class="logo cursive">
        <span style="color:tomato">ANI</span>FIND
      </h1>
      <hr style="color:tomato;width:50%;padding-bottom:60px">
      <!--  H1 can have 2 designs: "logo" and "logo cursive"           -->

      <div class="content">
        <h4 class="motto">Search anime by screenshot.</h4>
        <div class="subscribe">
          <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm6-6 col-sm-offset-3 ">
              <form class="form-inline" role="form" action="result.php" method="POST">
                <div class="form-group">
                  <input type="text" name="img-url" class="form-control transparent" placeholder="Paste image url here">
                </div>
                <button type="submit" class="btn btn-danger btn-fill">Search</button>
                <h5 class="info-text" style="opacity:0.8;"><a style="color:tomato"><?php echo $user['limit']; ?></a>
                  tries left.</h5>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <div class="container">
        Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>
      </div>
    </div>
  </div>
</body>
<script src="js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>

</html>