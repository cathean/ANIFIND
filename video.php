<?php 
  $id = $_GET['id'];
  $filename = $_GET['filename'];
  $at = $_GET['at'];
  $token = $_GET['token'];

  $video = "https://media.trace.moe/video/".$id."/".$filename."?t=".$at."&token=".$token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <center>
    <video id="player" volume="0.5" autoplay="" controls>
      <source src="<?php echo $video; ?>">
    </video>
  </center>
</body>
</html>