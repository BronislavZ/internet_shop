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
	<link rel="stylesheet" type="text/css" href="/css/style.css">
  <link href="https://use.fontawesome.com/releases/v5.0.2/css/all.css" rel="stylesheet">
</head>
<body>
<div class="wraper">

  <?php include"/includes/header_nav.php";?>


  <?php include"/includes/row_bestshop.php";?>

	<main id="40">
    <div style="width: 100%; background-color: white; padding: 30px 0 100px; margin: 0 auto;">
      <h1 style="text-align: center; color: black;">Добавление товара</h1>
      <div style="width: 40%; margin: 0 auto;">
          <?php echo "$msg" . '<br>'; ?>

          <form action="addarticlefin.php/#40" method="post" enctype="multipart/form-data">
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
    </div>

    <hr>

    <div class="info_company">
      <h2><b>Интернет магазин модной одежды ROYAL STYLE: удобный и выгодный шопинг</b></h2>
      </br>
      <p>
        <b>Интернет магази Royal Style делает шопинг доступным, удобным, выгодным</b>. Выбрать, купить одежду очень легко: в ассортименте есть буквально все.
        Преимущества интернет магазина Royal Style - доступные цены, широкий, регулярно обновляемый ассортимент. В Royal Style можно купить одежду любой известной марки - представлена продукция ведущих зарубежных брендов.
      </p>
      <p>
        <b>Каждая новая модная коллекция своевременно появляется в каталоге Royal Style.</b> Ассортимент одежды всегда остается актуальным. Новинки отслеживать легко: достаточно подписаться на рассылку магазина, чтобы быть в курсе появления в ассортименте новой одежды от любимых брендов и дизайнеров.
      </p>
      <p>
        <b>Royal Style предлагает исключительно удобные условия оплаты, доставки заказов.</b> Оплатить заказ покупатель может банковской картой или в отделении почты после осмотра товара. Оперативная доставка осуществляется курьерской службой, Новой почтой. Если предметы гардероба по каким-либо причинам не подошли, от них можно отказаться. 
      </p>
      <p>
        <b>Выгодные условия действуют для постоянных покупателей Royal Style, они могут рассчитывать на дополнительные скидки.</b>
      </p>
      <p><b>Оцените преимущества покупок в Royal Style уже сейчас!</b></p>
    </div>

	</main>

  <?php include"/includes/footer.php";?>

</div>
</body>
</html>