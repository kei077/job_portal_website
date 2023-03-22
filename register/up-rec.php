<?php

session_start();

$DB_DSN="mysql:host=localhost;dbname=techjob";
$DB_USER="root";
$DB_PASS="";
try
{
    // la partie de la connexion
    $conn = new PDO($DB_DSN,$DB_USER,$DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e)
{
    echo $e->getMessage();    
}


if (isset($_FILES['file']) && isset($_POST['submit']))
{
    // pour extraire les differents informations sur notre fichier
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    // Emplacement temporaire du fichier
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    // Extraction de l'extension
    $fileExt= explode('.', $fileName );
    // JPG->jpg end pour recuperer la derniere partie de nom du fichier
    $fileActualExt = strtolower(end($fileExt));
    // on va commencer de faire des tests sur les differents informations associees au fichier
    $allowed = array('jpg','jpeg','png');
    if(in_array($fileActualExt, $allowed)) {
        if($fileError === 0) {
            // verification sur la tailee du fichier 
            if($fileSize < 1000000) {
                $fileNameNew = uniqid('',true).".".$fileActualExt ;
                $fileDestination ='upload1/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                
                // modification de la requête SQL pour insérer le nom de fichier
                try {
                    $sql=$conn->prepare('UPDATE recruteur SET photo = :upic WHERE id_recruteur = :id');
                    $sql->bindParam(':upic',$fileNameNew);
                    $sql->bindParam(':id', $_SESSION['id']);
                    $sql->execute();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else{
                $message= "your file is too big!";
            }
        
         }
         else{  $message="There was an error uploading your file!";}
        }
        else{
            $message="You cannot upload files of this type";
        }
      
  //on envoie l'utilisateur vers la page register-cand-comp.php si tout va correctement
   if ($message !='')  {echo $message;} 

   else{
    
    header('Location:../dashboard/dashboard.php');}
  
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
    <title>Document</title>
    <style>
        .col1{
	background-color:rgb(255, 255, 255);
	height: auto;
	padding:10px 20px;
	border-radius: 20px;
	box-shadow: 2px 2px 8px gray;
	border: 2px solid transparent;	
    width: 300px;
    height: 450px;
    text-align:center;
    margin-left:60px;
}
.btn{
    height: 35px;
    background-color:#050642;

}
.col1:hover{
    border:1px solid #050642;
}
.cc{
position:relative;
bottom:440px;
left:210px;
height:390px;
}
        </style>
</head>
<body>
<nav id="navb" class="navbar navbar-expand-lg " >
        <div class="container ">
        <a class="navbar-brand" href="../index.php"><img  class="logo" src="logo.png" height="60px" width="100px" alt="link" class="img"></a>
   </div> 
	</nav>
   
   <div class="container">
  
    <div class="  col1">
    <form action="up-rec.php" method="post" enctype="multipart/form-data">
        <img src="add-image-rec.png" alt="">
        <input type="file" name="file" >
        <input type="submit" name="submit"  class="btn" value="Upload">
    </form>
    <a href="../dashboard/login.php"> <button class="btn">Skip>></button></a>
    </div>   
    </div>
    <img src="avatar4.png"  class="cc" alt=""> 
   
</body>
</html>
