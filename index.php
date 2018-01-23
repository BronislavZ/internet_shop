<?php
require "/config/config.php";
?>




<!DOCTYPE html>
<html>
<head>
	<title>Royal Style</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
  <link href="https://use.fontawesome.com/releases/v5.0.2/css/all.css" rel="stylesheet">
</head>
<body>
<div class="wraper">

  <?php include"/includes/header_nav.php";?>

  <div class="row">
    <img src="/img/maincategories/112.jpg" alt="">
  </div>

  <?php include"/includes/row_bestshop.php";?>

	<main>

		<section id="new_items_wraper">
			<h2 id="title_new_it">Новинки</h2>

			<div class="new_items_div">
        <?php 
        $query = $connection->prepare("SELECT * FROM `catalog` ORDER BY `pubdate` DESC LIMIT 6" );
        $query->execute();
        $last_six_items= $query->fetchAll();
        foreach ($last_six_items as $item) {
          ?>
          <article class="new-item">
            <a href="item.php?id=<?=$item['id']?>" title="<?=$item['name']?>">
            <div class="img_goods_wraper">
              <img src="/img/catalog/item (<?=$item['id']?>)/2.jpg" alt="">
              <img src="/img/catalog/item (<?=$item['id']?>)/1.jpg" class="second_img_goods" alt="">
            </div>
            <p><?=$item['brand']?></p>
              <h3><?=$item['name']?></h3>
            <p><?=$item['price']?> uah</p>
            </a>
          </article>
        <?php
        }
        ?> 
			</div>
		</section>

		<div class="categorie_wraper">	
			<div class="categorie_wraper_row"> 
         <div class="categorie_wraper_row_in">
            <a href="catalog.php?maincat=1">
              <img src="/img/maincategories/1.jpg" alt="">
              <span class="inner">
                <h2 class="title_cat">Одежда</h2>
                <br><br>
                <div class="inner-div"><span class="button_text">Перейти в раздел</span></div>
              </span>
            </a>
         </div><div class="categorie_wraper_row_in">
            <a href="catalog.php?maincat=2">
              <img src="/img/maincategories/3.jpg" alt="">
              <span class="inner">
                <h2 class="title_cat">Обувь</h2>
                <br><br>
                <div class="inner-div"><span class="button_text">Перейти в раздел</span></div>
              </span>
            </a>
         </div><div class="categorie_wraper_row_in">
            <a href="catalog.php?maincat=3">
              <img src="/img/maincategories/4.jpg" alt="">
              <span class="inner">
                <h2 class="title_cat">Аксесуары</h2>
                <br><br>
                <div class="inner-div"><span class="button_text">Перейти в раздел</span></div>
              </span>
            </a>
         </div>
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
        <b>Royal Style предлагает исключительно удобные условия оплаты, доставки заказов.</b> Оплатить заказ покупатель может банковской картой или в отделении почты после осмотра товара. Оперативная доставка осуществляется курьерской службой Новой почтой. Если предметы гардероба по каким-либо причинам не подошли, от них можно отказаться.  
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