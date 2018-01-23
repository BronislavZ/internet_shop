<?php
require "/config/config.php";

$connection->exec("SET NAMES UTF8");






$categories_choosed= "...";

  if(count($_POST)  > 0){
// екранизация всех полученых данных
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $brand = htmlspecialchars($_POST['brand']);
    $price = htmlspecialchars($_POST['price']);
    $categories_choosed = htmlspecialchars($_POST['categorie']);
    $description = htmlspecialchars($_POST['description']);




// проверки заполнености формы

    if (strlen($name)<2) {
      $msg = 'Укажите имя по длинее';
    }elseif (strlen($name)>100) {
      $msg = 'Сократите имя';
    }elseif (strlen($brand)<2) {
      $msg = 'Название бренда слишком короткое';
    }elseif (strlen($brand)>100) {
      $msg = 'Название бренда слишком длинное';
    }elseif (!is_numeric($price)) {
      $msg = 'Цена должна быть только из цифер';
    }elseif (strlen($price)<2) {
      $msg = 'Слишком маленькая цена';
    }elseif (strlen($price)>7) {
      $msg = 'Слишком большая цена';
    }elseif (strlen($categories_choosed)<4) {
      $msg = 'Выберите категорию из списка';
    }elseif (strlen($categories_choosed)>40) {
      $msg = 'Выберите категорию из списка';
    }elseif (strlen($description)<2) {
      $msg = 'Заполните поле с описанием';
    }elseif (strlen($description)>2000) {
      $msg = 'Заполните поле с описанием';
    }elseif (count($_FILES['upFile']['name'])<2) {
      $msg = 'Должно быть неменьше 2-ух фото';
    }elseif (count($_FILES['upFile']['name'])>5) {
      $msg = 'Должно быть небольше 5-ти фото';
    }else{

// проверка на существование папки, если да удалить ее с содержимым
$structure = "img/catalog/item (". $id .")/";
if (file_exists($structure)) {
    $files = glob($structure."*");
    $c = count($files);
    if (count($files) > 0) {
        foreach ($files as $file) {      
            if (file_exists($file)) {
            unlink($file);
            }   
        }
    }
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
// вставка товара в базу
      $query = $connection->prepare("INSERT INTO `catalog` SET id=:id, name=:name, brand=:brand, main_categories=:main_categories, categories=:categories, price=:price, description=:description ");
      $params = array('id'=> $id, 'name' => $name , 'brand'=> $brand, 'price'=> $price, 'categories'=> $categories, 'main_categories'=> $main_categories, 'description'=> $description);
      $query -> execute($params);

// перемещение файла
        if (isset($_FILES['upFile'])) {
           $errors = array();

           $i=0;
           while ($i<count($_FILES['upFile']['name']) ) {
           $file_name = $_FILES['upFile']['name'][$i];
           $file_size = $_FILES['upFile']['size'][$i]; 
           $file_tmp = $_FILES['upFile']['tmp_name'][$i];
           $file_type = $_FILES['upFile']['type'][$i];
           $file_ext = strtolower(end(explode('.', $_FILES['upFile']['name'][$i])));

           $expensions = array("jpeg", "jpg","png" );

           $i++;

            if ($file_size > 2097152) {
              $errors[] = 'Файл должен быть менее 2ух мегабайт';
            }
            if (empty($errors) == true) {
              $file_directory  = "img/catalog/item (". $id .")/".$i.".".$file_ext;//здесь имя файла
              move_uploaded_file($file_tmp, "$file_directory");  
            }else{
              print $errors;
            }
           }
        }
// очистка переменных 
      $id = false;
      $name = false;
      $brand = false;
      $price = false;
      $categories_choosed = false;
      $description = false;
      
      $msg = 'Товар создан!';
    }
  }else{
    $msg = 'Заполните поля';
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
  <script type="text/javascript" src="css/jquery-3.2.1.js" ></script>
</head>
<body>


<style type="text/css">
input {
  width: 300px;
  font-size: 13px;
  padding: 6px 0 4px 10px;
  border: 1px solid #cecece;
  background: #F6F6f6;
  border-radius: 8px;
}
textarea {
  /* = Убираем скролл */
  overflow: auto;

  /* = Убираем увеличение */
  resize: none;
  width: 300px;
  height: 50px;

  /* = Добавим фон, рамку, отступ*/
  background: #f6f6f6;
  border: 1px solid #cecece;
  border-radius: 8px 0 0 0;
  padding: 8px 0 8px 10px;
}

select {
    width: 310px;
  font-size: 13px;
  padding: 6px 0 4px 10px;
  border: 1px solid #cecece;
  background: #F6F6f6;
  border-radius: 8px;
}

.submit {
  cursor: pointer;
  border: 1px solid #cecece;
  background: #f6f6f6;
  box-shadow: inset 0px 20px 20px #ffffff;
  border-radius: 8px;
  padding: 8px 14px;
  width: 120px;
}
.submit:hover {
  box-shadow: inset 0px -20px 20px #ffffff;
}
.submit:active {
  margin-top: 1px;
  margin-bottom: -1px;
  zoom: 1;
}
</style>

    <?php echo "$msg" . '<br>'; ?>


<form action="" method="post" enctype="multipart/form-data">
  <input type="file" name="upFile[]"  multiple><br><br>
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
  </select>
  <br><br>
  <textarea name="description" required="" placeholder="description" ><?=$description?></textarea><br><br>
  <input class="submit" type="submit" value="Добавить"><br><br>
</form>
    
</div>
</body>
</html>