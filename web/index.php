<!DOCTYPE html>
<html lang="pt-PT">
<head>
<title>Projeto ExpoCIC</title>
<link rel="icon" type="image/x-icon" href="assets/logo_ExpoCIC.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<style>
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
.w3-third img{margin-bottom: -6px; opacity: 0.8; cursor: pointer}
.w3-third img:hover{opacity: 1}

.downloadButton {
  background-color: #4CAF50; /* Set a cool green color */
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  font-weight: bold;
  border-radius: 5px;
  transition: background-color 0.3s ease; /* Add a smooth transition */
}

.downloadButton:hover {
  background-color: #3e8e41; /* Darken the color on hover */
}

.ver-mais {
  border: none;
  color: white;
  width:100%;
  text-align: center;
  margin-top:5px;
  text-decoration: underline;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
  color:black;
}

.ver-mais:hover {
  background-color: gray;
  color:white
}
</style>
<script>


var start = 0;
var numToShow = 9;
var nImgs = 0;
function showMoreImages() {
  // Get the slice of the array to show
  var imagePaths = JSON.parse(document.getElementById('imagePaths').value);
  var observacoes = JSON.parse(document.getElementById('observacoes').value);

  var imageSlice = imagePaths.slice(start, start + numToShow);
  
  // Loop through the slice and create an img element for each image path
  var container = document.getElementById('imageContainer');
  for (var i = 0; i < imageSlice.length; i++) {
    // Create a new div every 3 images
    if (i % 3 == 0) {
      var div = document.createElement('div');
      div.className = 'w3-third';
      container.appendChild(div);
    }
    nImgs++;
    // Create a new img element with the specified attributes
    var img = document.createElement('img');
    img.src = imageSlice[i];
    img.style.width = '100%';
    img.onclick = function() {
      onClick(this);
    };
    
    // Add the img element to the current div
    div.appendChild(img);
  }
  
  // Update the start index for the next slice
  start += numToShow;
  if(nImgs >= imagePaths.length) {

    document.getElementById("divMais").style.display = "none";
  }
}


// Script to open and close sidebar
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("mySidebar").style.width = "50%";
  document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
  document.getElementById("mySidebar").style.width = "15vw";
}

// Modal Image Gallery
function onClick(element) {
  document.getElementById("downloadButton").href = element.src;
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
  var captionText = document.getElementById("caption");
  captionText.innerHTML = element.alt;
}

</script>
</head>
<body class="w3-light-grey">

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-white w3-animate-left w3-text-grey w3-collapse w3-top w3-center" style="z-index:3;width:15vw;font-weight:bold" id="mySidebar"><br>
<img class="img-fluid" style="margin:auto;" src="assets/logo_ExpoCIC.png" width="300px" >  
<h3 class="w3-padding-32 w3-center">Projeto ExpoCIC<br>Polaroid</b></h3>
<div id="sidebarContent">
  <!-- max-height:60% -->
  <div id="categorias" style="max-height: 60%; overflow-y: auto;">
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-padding w3-hide-large">CLOSE</a>

    <a href="?categoria=Coletivo" onclick="w3_close()" class="w3-bar-item w3-button">Coletivo</a> 
    <?php
    
      require file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php';

      $conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

      // Check for errors
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // retrieve distinct categories from the fotografias table
      $sql = "SELECT DISTINCT categoria FROM fotografias";
      $result = $conn->query($sql);

      // check for results
      if ($result->num_rows > 0) {
        // loop through results and print each category
        while ($row = $result->fetch_assoc()) {
          $row['categoria'] = str_replace('/', '', $row['categoria']);
          echo '<a href="?categoria=' . $row['categoria'] . '" onclick="w3_close()" class="w3-bar-item w3-button">' . $row['categoria'] . '</a>';
        }
      } else {
        echo "No categories found.";
      }
      
    ?>

  </div>
  <div style="height:4vh;position:absolute;bottom:0;width:100%;border-top:solid 2px gray;background-color:white;" class="w3-center w3-padding-30">Afonso Saraiva e Gabriel Sousa</div>
</div>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-white w3-xlarge w3-padding-16">

  <span class="w3-left w3-padding" style="font-size:21px;">Projeto ExpoCIC - Polaroid</span>
  <a href="javascript:void(0)" class="w3-right w3-button w3-white" onclick="w3_open()">☰</a>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->

<div class="w3-main" style="margin-left:15vw">

  <!-- Push down content on small screens -->
   
  <div class="w3-hide-large" style="margin-top:83px"></div>
  
  <!-- Photo grid -->
  <div id = "imageContainer" class="w3-row">
    
    <?php 

      $images = array();
      $array_fotos = array();
      $array_fotoMolduraCharacter = array();
      $array_observacoes = array();
      // Make the query to the database
      if (isset($_GET['categoria']) && $_GET['categoria'] != "Coletivo") {
        $categoria = mysqli_real_escape_string($conn, $_GET['categoria']);
        $categoria = $categoria . "/";
        // Prepare the SQL statement
        $sql = "SELECT categoria,fotoMoldura,fotoMolduraCharacter,observacoes FROM fotografias WHERE fotoMolduraCharacter != '' AND categoria = ?";
      
        // Prepare the statement and bind the 'categoria' parameter
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $categoria);
      
        // Execute the query
        mysqli_stmt_execute($stmt);
      
        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $categoria_result, $fotoMoldura_result, $fotoMolduraCharacter_result, $observacoes_result);
        
        $iterator = 0;
        
        while (mysqli_stmt_fetch($stmt)) {
          $array_fotos[$iterator] = UPLOADS_DIR . $categoria_result . $fotoMoldura_result;
          $array_fotos[$iterator+1] = UPLOADS_DIR . $categoria_result . $fotoMolduraCharacter_result;
          $array_observacoes[$iterator] = $observacoes_result;
          $array_observacoes[$iterator+1] = $observacoes_result;
          $iterator += 2;
        }
        mysqli_stmt_close($stmt);
      }
      else{
        $sql = "SELECT categoria,fotoMoldura,fotoMolduraCharacter,observacoes FROM fotografias WHERE fotoMolduraCharacter != ''";
        $result = mysqli_query($conn, $sql);

        $iterator = 0;

        while ($row = mysqli_fetch_assoc($result)) {

          $array_fotos[$iterator] = UPLOADS_DIR . $row['categoria'] . $row['fotoMoldura'];
          $array_fotos[$iterator+1] = UPLOADS_DIR . $row['categoria'] . $row['fotoMolduraCharacter'];
          $array_observacoes[$iterator] = $row['observacoes'];
          $array_observacoes[$iterator+1] = $row['observacoes'];
          $iterator += 2;
        }
      }

      $json_fotos = json_encode($array_fotos);
      $json_observacoes = json_encode($array_observacoes);
      $conn->close();
    ?>
    <input type="hidden" id="imagePaths" value='<?php echo $json_fotos; ?>'>
    <input type="hidden" id="observacoes" value='<?php echo $json_observacoes; ?>'>

    <?php echo "<script>showMoreImages();</script>"; ?>

  <!-- <div class="w3-third">
    <img src="molduras/frame.png" style="width:100%" onclick="onClick(this)" alt="A boy surrounded by beautiful nature">
    <img src="molduras/frame.png" style="width:100%" onclick="onClick(this)" alt="What a beautiful scenery this sunset">
    <img src="molduras/frame.png" style="width:100%" onclick="onClick(this)" alt="The Beach. Me. Alone. Beautiful">
  </div> -->
  </div>
  
  <!-- Pagination -->
  <div id="divMais" class="w3-center ver-mais" style="max-height:4vh;" onclick="showMoreImages()">
    <span>Mostrar mais fotos!</span>
  </div>
  
  <!-- Modal for full size images on click-->
  <div id="modal01" class="w3-modal" style="padding-top:0" onclick="this.style.display='none'">
    <span class="w3-button w3-black w3-xlarge w3-display-topright">×</span>
    <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
      <img id="img01" class="w3-image">
      <p id="caption"></p>
      <a id="downloadButton" href="path/to/image.jpg" download>
        <button class="downloadButton" >Download</button>
      </a>
    </div>
  </div>
<!-- End page content -->
</div>

</body>
</html>
