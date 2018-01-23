<?php
require "/config/config.php";

$categories_choosed= "...";
  if(count($_POST)  > 0)
  {
// екранизация всех полученых данных
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $brand = htmlspecialchars($_POST['brand']);
    $price = htmlspecialchars($_POST['price']);
    $categories_choosed = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);
// здесь c categories_choosed достанем номер категории и главной категории.
      $queryfor_categ_arr = "SELECT * FROM `categories` WHERE `name_categorie` = '". $categories_choosed ."'";
      $query = $connection -> prepare("$queryfor_categ_arr");
      $query -> execute();
      $categ_arr = $query->fetchAll();
    $categories = $categ_arr['0']['id'];
    $main_categories = $categ_arr['0']['main_categories'];
// проверки заполнености формы
    if (strlen($name)<2) {
      $msg = 'Lenght of name not real';
    }elseif (strlen($brand)<2) {
      $msg = 'Lenght of email short';
    }else{

// вставка товара в базу
      $query = $connection->prepare("INSERT INTO `catalog` SET id=:id, name=:name, brand=:brand, main_categories=:main_categories, categories=:categories, price=:price, description=:description ");
      $params = array('id'=> $id, 'name' => $name , 'brand'=> $brand, 'price'=> $price, 'categories'=> $categories, 'main_categories'=> $main_categories, 'description'=> $description);
      $query -> execute($params);

// очистка переменных 
    $id = false;
    $name = false;
    $brand = false;
    $price = false;
    $categories_choosed = false;
    $description = false;
// $msg = 'your comment added';
    }

  }else{
    $msg = 'put info inside field';
  }


// перемещение файла
if (isset($_FILES['image'])) {
 $errors = array();
 $file_name = $_FILES['image']['name'];
 $file_size = $_FILES['image']['size'];
 $file_tmp = $_FILES['image']['tmp_name'];
 $file_type = $_FILES['image']['type'];
 $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

 $expensions = array("jpeg", "jpg","png" );
  if ($file_size > 2097152) {
    $errors[] = 'Файл должен быть менее 2ух мегабайт';
  }
  if (empty($errors) == true) {
    move_uploaded_file($file_tmp, "img/".$file_name);
    echo "Success";  
  }else{
    print $errors;
  }
}
// категории для селекта
$query = $connection->prepare("SELECT `name_categorie` FROM `categories`" );
$query->execute();
$all_categoriees_arr= $query->fetchAll();
// самый большой id из базы и делаем следующий номер
  $query = $connection->prepare("SELECT `id` FROM `catalog` ORDER BY `id` DESC LIMIT 1" );
  $query->execute();
  $biggest_id= $query->fetchAll();
$next_id = $biggest_id['0']['id']+1;
?>


<!DOCTYPE html>
<html>
<head>
	<title>Main</title>
	<meta charset="utf-8">
</head>
<body>




    <?php echo "$msg" . '<br>'; ?>


<form action="" method="post" enctype="multipart/form-data">
   <!-- name="userFile[]" type="file" multiple -->
  <input type="file" name="image">
  <input type="" name="id" value="<?=$next_id?>">
  <input type="" name="name" placeholder="name" value="<?=$name?>">
  <input type="" name="brand" placeholder="brand" value="<?=$brand?>">
  <input type="" name="price" placeholder="price" value="<?=$price?>">
  <select name = "categorie" >
    <option><?=$categories_choosed?></option>
    <?php 
    foreach ($all_categoriees_arr as $categ) {
    echo  "<option>".$categ['name_categorie']."</option>";
    }
    ?> 
  </select>
  <textarea name="description" placeholder="description" value="<?=$description?>"></textarea>
  <input type="submit" value="Добавить">
</form>


















<!-- 
<?php

  if(count($_POST)  > 0)
  {

    //trim();
    // strip_tags();
    //htmlspecialchars();   

    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $brand = htmlspecialchars($_POST['brand']);
    $price = htmlspecialchars($_POST['price']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);
    
    

    if (strlen($name)<2) {
      $msg = 'Lenght of name not real';
    }elseif (strlen($email)<7) {
      $msg = 'Lenght of email short';
    }elseif (strlen($text)<10) {
      $msg = 'Put your comment';  
    }else{

      $query = $connection->prepare("INSERT INTO `coments` SET author=:name, email=:email, text=:text ");
      $params = array('name'=> $name, 'email' => $email , 'text'=> $text);
      $query -> execute($params);


      $msg = 'your comment added';
            $name=false;
      $email = false;
      $text = false;

    }

  }else{
    $msg = 'put info inside field';
  }

      $query = $connection->prepare("SELECT * FROM `coments` ORDER BY `pubdate` DESC" );
      $query->execute();
      $comments= $query->fetchAll();



?>

<form method="POST" action="">
    <?php echo "$msg" . '<br>'; ?>
  <input type="text" placeholder="Ваш логин" name="name" value="<?=$name?>">
  <input type="text" placeholder="Ваш email" name="email"  value="<?=$email?>">
  <br>
  <textarea name="text" required=""  placeholder="Your comment ..."><?=$text?></textarea>
  <hr>
  <input type="submit" value="Отправить">

</form>




<div class="comments">
  <?foreach ($comments as  $comment) :?>
    <div class="item">
       <span><?=$comment['author']?></span><br>
       <strong><?=$comment['email']?></strong><br>
       <div><?=$comment['text']?></div>
       <br>
    </div>
  <?endforeach?>
</div>


 -->


    
</div>
</body>
</html>