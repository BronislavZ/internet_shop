<?php
require "/config/config.php";
?>

<?php
  $item_id = htmlspecialchars($_GET['id']);
  $query_for_items = "SELECT * FROM `catalog` WHERE `id` ='" . $item_id . "'";
  $query = $connection->prepare("$query_for_items");
  $query->execute();
  $catalog_items_arr= $query->fetchAll();

// здесь будет код для смены картинок
$photo = htmlspecialchars($_GET['photo']);
if (is_numeric($photo)) {
$photo_num = $photo;
}else{
$photo_num = 1;
}

$dir = opendir("img/catalog/item (".$catalog_items_arr['0']['id'].")/");
$count_file = 0;
while($file = readdir($dir)){
if($file == '.' || $file == '..' || is_dir("img/catalog/item (".$catalog_items_arr['0']['id'].")/". $file)){
continue;
}
$count_file++;
}
closedir();


if ($photo_num == 1) {
$back_photo = $count_file;
$next_photo=2;
}elseif ($photo_num == $count_file) {
$back_photo = ($count_file-1);
$next_photo=1;
}else{
$next_photo=($photo_num+1);
$back_photo=($photo_num-1);
}


?>



<!DOCTYPE html>
<html>
<head>
	<title><?=$catalog_items_arr['0']['name']?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
  <link href="https://use.fontawesome.com/releases/v5.0.2/css/all.css" rel="stylesheet">
</head>

<body>
<div class="wraper">

  <?php include"/includes/header_nav.php";?>

  <?php include"/includes/row_bestshop.php";?>

	<main class="main_item_page clearfix">
    <div class="item_info_wraper clearfix">

      <div id="item_photos_block">
        <div class="img_goods_wraper">
            <a href="/item.php?id=<?=$item_id?>&photo=<?=$back_photo?>&#item_photos_block"><img  src="/img/catalog/item (<?=$catalog_items_arr['0']['id']?>)/<?=$photo_num?>.jpg" alt=""></a>
        </div> 

        <div class="item_all_photos_row">  
          <span class="item_icon" >
            <a href="/item.php?id=<?=$item_id?>&photo=<?=$back_photo?>&#item_photos_block"><i class="item_turn_photo fas fa-caret-left fa-3x"></i></a>
          </span>
              <?php
              for ($num_photo=1; $num_photo <= $count_file  ; $num_photo++) { 
              ?>
          <span class="item_all_photos">
            <a href="/item.php?id=<?=$item_id?>&photo=<?=$num_photo?>&#item_photos_block"><img  class="item_num1" src="/img/catalog/item (<?=$catalog_items_arr['0']['id']?>)/<?=$num_photo?>.jpg" alt=""></a>
          </span>
              <?php
              }
              ?>
          <span class="item_icon" >
            <a href="/item.php?id=<?=$item_id?>&photo=<?=$next_photo?>&#item_photos_block"><i class="item_turn_photo fas fa-caret-right fa-3x"></i></a>
          </span>
        </div>
      </div>


      <div class="item_info_block">
        <div class="item_name">
          <p class="item_descr"><?=$catalog_items_arr['0']['name']?></p>
        </div>
        <div class="item_name">
          <h2 id="name_of_item_new"><?=$catalog_items_arr['0']['brand']?></h2>
        </div>
        <div id="item_descr">
          <p class="item_descr"><?=$catalog_items_arr['0']['price']?> UAH</p>
        </div>


        
        <div class="text_info_item">
          <hr>
            <p><b>Описание:</b><p>
            <p><?=$catalog_items_arr['0']['description']?></p>
          <hr>
            <p><b>Ознакомьтесь, пожалуйста, с условиями оплаты и доставки.</b></p>
            <p>Номер телефона для связи <?=$config['mobile_phone']?></p>
            <p>Так же связатся с нами можно через <a class="item_text_link" href="<?=$config['instagram_url']?>"><i class="fab fa-instagram "> Instagram</i></a> или  <a class="item_text_link" href="<?=$config['vk_url']?>"><i class="fab fa-vk"> Vkontakte</i></a>.</p>
          <br>
            <p><b>Способы доставки:</b> Новой Почтой, возможна доставка курьером, Укрпочтой, также можете посетить наш магазин, купить выбраную вами вещь после примерки. </p>
          <br>
            <p><b>Способы оплаты:</b> наличными, безналичный расчет(перевод на карту Приватбанка), наложенный платеж (отправка товара только после символичной предоплаты которая покроет расходы на услуги Новой почты).</p>
          <br>
            <p>Если вещь по каким-то причинам не подошла вам, мы без проблем обменяем ее на нужный размер или на другой товар в установленный законом срок. Обмен и возврат возможны, только если вещь не была в употреблении, сохранены товарный вид (а также ярлыки, этикетки, упаковка).</p>
            <hr>     
        </div>


        <div class="our_best_sides_row">
          <div class="our_best_sides">
            <img class="our_best_sides_img" src="/img/index/item_desc/1.svg" alt="">
            <p>Качество товара Original Quality</p>
          </div><div class="our_best_sides">
            <img class="our_best_sides_img" src="/img/index/item_desc/2.svg" alt="">
            <p>Выбор удобного срособа оплаты</p>
          </div><div class="our_best_sides">
            <img class="our_best_sides_img" src="/img/index/item_desc/3.svg" alt="">
            <p>Доставка по всем регионам Украины</p>
          </div><div class="our_best_sides">
            <img class="our_best_sides_img" src="/img/index/item_desc/4.svg" alt="">
            <p>Возможность обмена и возврата товара</p>
          </div>
        </div>
      </div>
    </div>
	</main>

  <?php include"/includes/footer.php";?>

</div>
</body>
</html>