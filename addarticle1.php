<?php
require "/config/config.php";

// теперь нужно сделать добавление файлов масово
// кинуть проверку на минимум 2 файла максимум 5
// все добавленые файлы переименововать 1 2 3 4 5




$categories_choosed= "...";

  if(count($_POST)  > 0){
// екранизация всех полученых данных
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $brand = htmlspecialchars($_POST['brand']);
    $price = htmlspecialchars($_POST['price']);
    $categories_choosed = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);

// проверка на существование папки, если да удалить ее с содержимым
$structure = "img/catalog/item (". $id .")/";
if (file_exists($structure)) {
  rmdir($structure);
}
//создание папки файла
if (!mkdir($structure, 0777, true)) {
    die('Не удалось создать директории...');
}

// здесь c categories_choosed достанем номер категории и подкатегории.
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
            $file_directory  = "img/catalog/item (". $id .")/".$file_name;
            move_uploaded_file($file_tmp, "$file_directory");
            echo "Success";  
          }else{
            print $errors;
          }
        }
// очистка переменных 
      $id = false;
      $name = false;
      $brand = false;
      $price = false;
      $categories_choosed = false;
      $description = false;
      
      $msg = 'your comment added';
    }
  }else{
    $msg = 'put info inside field';
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
  <input type="file" name="image"><br><br>
  <input type="" name="id" required="" value="<?=$next_id?>"><br><br>
  <input type="" name="name" required="" placeholder="name" value="<?=$name?>"><br><br>
  <input type="" name="brand" required="" placeholder="brand" value="<?=$brand?>"><br><br>
  <input type="" name="price" required="" placeholder="price" value="<?=$price?>"><br><br>
  <select name = "categorie" >
    <option><?=$categories_choosed?></option>
    <?php 
    foreach ($all_categoriees_arr as $categ) {
    echo  "<option>".$categ['name_categorie']."</option>";
    }
    ?> 
  </select><br><br>
  <textarea name="description" required="" placeholder="description" value="<?=$description?>"></textarea><br><br>
  <input type="submit" value="Добавить"><br><br>
</form>



    
</div>
</body>
</html>