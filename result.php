<?php
  require 'vendor/autoload.php';

  function search_request($img_url){
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://trace.moe/api/search?url=".$img_url);
    
    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // mengembalikan hasil curl
    return $output;
  }

  //https://i.pinimg.com/originals/b5/e5/11/b5e511e5bc936ee3082208fa0443560d.jpg
  $img = $_POST['img-url'];
  $profile = search_request($img);

  // ubah string JSON menjadi array
  $profile = json_decode($profile, TRUE);
  $anilist_id = $profile['docs']['0']['anilist_id'];

  //echo "<pre>";
  //print_r($profile);
  //echo "</pre>";

  $video = "https://media.trace.moe/video/".$anilist_id."/".rawurlencode($profile['docs']['0']['filename'])."?t=".$profile['docs']['0']['at']."&token=".$profile['docs']['0']['tokenthumb'];
  // Anilist fetch data
  // Here we define our query as a multi-line string
  $query = '
  query ($id: Int) { # Define which variables will be used in the query (id)
    Media (id: $id, type: ANIME) { # Insert our variables into the query arguments (id) (type: ANIME is hard-coded in the query)
      id
      season
      format
      description
      startDate {
        year
        month
        day
      }
      endDate {
        year
        month
        day
      }
      episodes
      duration
      genres
      averageScore
      siteUrl
      studios {
        nodes {
          name
        }
      }
      coverImage {
        large
      }
    }
  }
  ';

  // Define our query variables and values that will be used in the query request
  $variables = [
      "id" => $anilist_id
  ];

  // Make the HTTP Api request
  $http = new GuzzleHttp\Client;
  $response = $http->post('https://graphql.anilist.co', [
      'json' => [
          'query' => $query,
          'variables' => $variables,
      ]
  ]);

  if ($anime = $response->getBody()) {
    $anime = json_decode($anime, true);
  }

  //echo "<pre>";
  //print_r($anime);
  //echo "</pre>";
  //exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>ANIFIND - Result Page</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">

  <!-- =======================================================
  * Template Name: TheEvent - v2.2.0
  * Template URL: https://bootstrapmade.com/theevent-conference-event-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
        <!-- Uncomment below if you prefer to use a text logo -->
        <h1><a href="index.php" style="font-family: 'Comfortaa', cursive">Ani<span>Find</span></a></h1>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="buy-tickets"><a href="index.php">Search Again</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- End Header -->

  <!-- ======= Intro Section ======= -->
  <section id="intro">
    <div class="intro-container" data-aos="zoom-in" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-6">
          <!-- Thumbnail image -->
          <img src=<?php echo $anime['data']['Media']['coverImage']['large']; ?> style="">
        </div>
        <div class="col-lg-6">
          <p class="mb-4 pb-0">
            <?php echo $profile['docs']['0']['title_romaji']." (".$profile['docs']['0']['title_english'].")"; ?></p>

          <table style="color: white; font-size: 24px; vertical-align: middle">
            <tr>
              <th>
              <a href="video.php?id=<?php echo $anilist_id; ?>&filename=<?php echo rawurlencode($profile['docs']['0']['filename']); ?>&at=<?php echo $profile['docs']['0']['at']; ?>&token=<?php echo $profile['docs']['0']['tokenthumb']; ?>" class="venobox play-btn mb-4" data-vbtype="iframe" data-autoplay="true"></a>
              </th>
              <th style="padding-left: 20px">
                Similarity
              </th>
              <th style="padding-left: 5px; color: #e0072f;">
                <?php echo round($profile['docs']['0']['similarity'] * 100)."%"; ?>
              </th>
            </tr>
            <tr>
              <th>From </th>
              <th>Episode <?php echo $profile['docs']['0']['episode']; ?></th>
            </tr>
          </table>

          <table style="color:white; max-width:100%;; white-space: nowrap">
            <tr>
              <th style="text-align: right; padding-right: 20px;">Score : </th>
              <th style="text-align: left;"><?php echo $anime['data']['Media']['averageScore']; ?> / 100</th>
            </tr>
            <tr>
              <th style="text-align: right; padding-right: 20px;">Genres : </th>
              <th style="text-align: left;">
                <?php
                $len = count($anime['data']['Media']['genres']);
                $i = 0;
                foreach ($anime['data']['Media']['genres'] as $arr) {
                  echo $arr;

                  if($i != $len) {
                    echo ", ";
                  }
                  $i = $i + 1;
                }
              
              ?>
              </th>
            </tr>
            <tr>
              <th style="text-align: right; padding-right: 20px;">Episodes : </th>
              <th style="text-align: left;"><?php echo $anime['data']['Media']['episodes']; ?>
                (<?php echo $anime['data']['Media']['duration']; ?> minutes)</th>
            </tr>
            <tr>
              <th style="text-align: right; padding-right: 20px;">Format : </th>
              <th style="text-align: left;"><?php echo $anime['data']['Media']['format']; ?></th>
            </tr>
            <tr>
              <th style="text-align: right; padding-right: 20px;">Season : </th>
              <th style="text-align: left;"><?php echo $anime['data']['Media']['season']; ?></th>
            </tr>
          </table>
        </div>
      </div>


      <br>
      <a href=<?php echo $anime['data']['Media']['siteUrl']; ?> class="about-btn scrollto">Anilist Webpage</a>
    </div>
  </section><!-- End Intro Section -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-lg-6">
            <h2>Summary</h2>
            <p><?php echo $anime['data']['Media']['description']; ?></p>
          </div>
          <div class="col-lg-3">
            <h3>Studios</h3>
            <p>
              <?php
                $len = count($anime['data']['Media']['studios']['nodes']);
                $i = 1;
                foreach ($anime['data']['Media']['studios']['nodes'] as $arr) {
                  echo $arr['name'];

                  if($i != $len) {
                    echo ", ";
                  }
                  $i = $i + 1;
                }
            ?>
            </p>
          </div>
          <div class="col-lg-3">
            <h3>Date Airing</h3>
            <p>From
              <?php echo $anime['data']['Media']['startDate']['day']."-".$anime['data']['Media']['startDate']['month']."-".$anime['data']['Media']['startDate']['year']; ?>
              <br>
              To
              <?php echo $anime['data']['Media']['endDate']['day']."-".$anime['data']['Media']['endDate']['month']."-".$anime['data']['Media']['endDate']['year']; ?>
            </p>
          </div>
        </div>
      </div>
    </section><!-- End About Section -->

  </main><!-- End #main -->

  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/superfish/superfish.min.js"></script>
  <script src="assets/vendor/hoverIntent/hoverIntent.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>