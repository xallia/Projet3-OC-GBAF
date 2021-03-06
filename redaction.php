 <?php
// connexion à la base de données
require_once('inc/connec.php');
    
if(isset($_POST['article_titre'], $_POST['article_contenu'])) 
{
    //Vérification que tout les champs sont remplis
    if(!empty($_POST['article_titre']) AND !empty($_POST['article_contenu'])) 
    {
      $article_titre = $_POST['article_titre'];
      $article_contenu = $_POST['article_contenu'];

      //affcihage des erreurs
      var_dump($_FILES);
      var_dump(exif_imagetype($_FILES['miniature']['tmp_name']));

      //insérer dans la base de données
      $ins = $bdd->prepare('INSERT INTO articles(titre, contenu, date_creation) VALUES (?, ?, NOW())');
      $ins->execute(array($article_titre, $article_contenu));
      $lastid = $bdd->lastInsertId();

      if(isset($_FILES['miniature']) AND !empty($_FILES['miniature']['name']))
      {
        // 3 pour le format png
        if(exif_imagetype($_FILES['miniature']['tmp_name']) == 3) 
        {
          $chemin = 'miniatures/'.$lastid.'.png';
          move_uploaded_file($_FILES['miniature']['tmp_name'], $chemin);
        }
      }
         $erreur = 'Votre article à bien été ajouté !';
        }
        else
        {
          $erreur = 'Veuillez remplir tous les champs!';
        }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rédaction</title>
  <meta charset="utf-8">
</head>
<body>
        <!-- formulaire pour insérer un article dans la base de données-->
        <form method="POST" enctype="multipart/form-data">
                <input type="text" name="article_titre" placeholder="titre" /> <br/> <br/> 
                <textarea name="article_contenu" placeholder="Contenu de l'article"></textarea> <br/> <br/> 
                <input type="file" name="miniature">
                <input type="submit" value="Envoyer l'article !" /> <br/>
        </form>
        <?php if(isset($erreur)) {echo $erreur;} ?>
</body>
</html>
