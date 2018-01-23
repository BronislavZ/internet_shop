<?php
require "/config/config.php";
?>

<?php

$maincat = htmlspecialchars($_GET['maincat']);
$cat = htmlspecialchars($_GET['cat']);
$sort_items = htmlspecialchars($_GET['sort']); 
$page = htmlspecialchars($_GET['page']);



// блок переменных для подставления в ссылку          
if (is_numeric($maincat)) {
  $link_maincat= "maincat=$maincat&" ;
}else{
  $link_maincat= false ;
}

if (is_numeric($cat)) {
  $link_cat= "cat=$cat&" ;
}else{
  $link_cat= false ;
}

if (is_numeric($page)) {
  $link_page= "page=$page&" ;
}else{
  $page = 1;
  $link_page= "page=1&" ;
}

if (strlen($sort_items)>2) {
  $link_sort= "sort=$sort_items&" ;
}else{
  $link_sort= false ;
}
// ok

//код для SQL запроса пагинации
$item_start_from = $page * 20 - 20;


//код сортировки и  sql для сортировки
$sort_by_date =  'sort=last">По дате <i style="color:#040404" class="fa fa-angle-down" aria-hidden="true"></i></a>';
$sort_by_price = 'sort=cheap">По Цене <i style="color:#040404" class="fa fa-angle-down" aria-hidden="true"></i></a>';

if ($sort_items == 'first') {
  $sort_by_date = 'sort=last" style="font-weight: bold;">По дате <i style="color:#040404" class="fa fa-angle-up" aria-hidden="true"></i></a>';
  $sql_order_by = ' ORDER BY `pubdate` ';
}elseif ($sort_items == 'cheap') {
  $sort_by_price = 'sort=expensive" style="font-weight: bold;">По Цене <i style="color:#040404" class="fa fa-angle-up" aria-hidden="true"></i></a>';
  $sql_order_by = ' ORDER BY `price` ';
}elseif ($sort_items == 'expensive') {
  $sort_by_price = 'sort=cheap" style="font-weight: bold;">По Цене <i style="color:#040404" class="fa fa-angle-down" aria-hidden="true"></i></a>';
  $sql_order_by = ' ORDER BY `price` DESC ';
}else{
  $sort_by_date = 'sort=first" style="font-weight: bold;">По дате <i style="color:#040404" class="fa fa-angle-down" aria-hidden="true"></i></a>';
  $sql_order_by = ' ORDER BY `pubdate` DESC ';
}


//sql для категорий
if (is_numeric($cat)) {
  $items_category = "WHERE `categories` ='" . $cat . "'";
}elseif (is_numeric($maincat)){
  $items_category = " WHERE `main_categories` ='" . $maincat . "'";
}else{
  $items_category = "";
}

//здесь же и конечный скл запрос
$query_for_items = "SELECT * FROM `catalog`" . $items_category . $sql_order_by . "LIMIT ".$item_start_from.",21";

// подсщет сколько товара найдено согласно критериям поиска
$query_for_count = "SELECT COUNT(*) as count FROM catalog ".$items_category. "" ; 
$count_items = $connection->query("$query_for_count")->fetchColumn();


//здесь функции для пагинации
if ($count_items<21) {
$show_pagination = 'style="display: none;"';
$count_pages=0;
}else{
  $count_pages=$count_items/20;
  $count_pages=(int)($count_pages+ 1);
}

// теперь  $count_items/20 получая количество страничек
// дальше для показа страничек используем цыкл форич




?>





<!DOCTYPE html>
<html>
<head>
	<title>Catalog</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css">

  <link href="https://use.fontawesome.com/releases/v5.0.2/css/all.css" rel="stylesheet">

</head>

<body>
<div class="wraper">

	<?php include"/includes/header_nav.php";?>

  <?php include"/includes/row_bestshop.php";?>

	<main class="main_class_catalogue clearfix">

    <h2 id="title_new_it">Каталог</h2>

    <aside class="catalog_nav">
      <h2 id="aside_catalogue_header">Категории:</h2>
      <ul class="catalog_nav_main_cat">
        <li><h3><a class="catalogue_cat_names" href="catalog.php?maincat=1">Одежда</a></h3>
          <ul class="catalog_nav_second">
            <li><a class="catalog_li_item" href="catalog.php?cat=1">Куртки</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=2">Джинсы</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=3">Штаны</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=4">Платья</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=5">Спортивные <br>костюмы</a></li>
          </ul>
        </li>
        <li><h3><a class="catalogue_cat_names" href="catalog.php?maincat=2">Обувь</a></h3>
          <ul class="catalog_nav_second">
            <li><a class="catalog_li_item" href="catalog.php?cat=6">Туфли</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=7">Кросовки</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=8">Ботинки</a></li>
          </ul>
        </li>
        <li><h3><a class="catalogue_cat_names" href="catalog.php?maincat=3">Аксесуары</a></h3>
          <ul class="catalog_nav_second">  
            <li><a class="catalog_li_item" href="catalog.php?cat=10">Сумки</a></li>
            <li><a class="catalog_li_item" href="catalog.php?cat=11">Кошельки</a></li>
          </ul>
        </li>
      </ul>
    </aside>

    <div class="wraper_pagination_sort" style=" margin-bottom:10px;">

      <div class="groupsortwrap">
        <ul class="sort-menu">
          <li>Сортировать:</li> 
          <li><a href="/catalog.php?<?=$link_maincat?><?=$link_cat?>   <?=$sort_by_date ?></li>
          <li><a href="/catalog.php?<?=$link_maincat?><?=$link_cat?>   <?=$sort_by_price ?></li>
        </ul>
      </div>

      <div class="pagination">
        <ul class="sort-menu">
          <li <?=$show_pagination?> >Страницы:</li>
          <li <?=$show_pagination?> >
            <a href=""><i style="color:#040404" class="fa fa-angle-left" aria-hidden="true"></i></a>
          </li>

              <?php
              $start_page = 0;
              while ( $start_page  < $count_pages) {
                 $start_page++;
              ?>
          <li>
            <a href="/catalog.php?<?=$link_maincat?><?=$link_cat?><?=$link_sort?>page=<?=$start_page?>"><?=$start_page?></a>
          </li>
              <?php
              }
              ?>

          <li <?=$show_pagination?> >
            <a href=""><i style="color:#040404" class="fa fa-angle-right" aria-hidden="true"></i></a>
          </li>
          <li>Всего товаров:  <?=$count_items?></li>
        </ul>
      </div>

    </div>

		<div class="catalog_items">
			<?php 
        $query = $connection->prepare("$query_for_items");
        $query->execute();
        $catalog_items_arr= $query->fetchAll();
      

        if(array_shift( $catalog_items_arr )) {
          foreach ($catalog_items_arr as $item) {
      ?>
          <article class="catalog_item">
            <a href="item.php?id=<?=$item['id']?>" title="<?=$item['name']?>">
              <div class="img_goods_wraper">
                <img src="/img/catalog/item (<?=$item['id']?>)/2.jpg" alt="">
                <img src="/img/catalog/item (<?=$item['id']?>)/1.jpg" class="second_img_goods" alt="">
              </div>
              <p class="item_text"><?=$item['brand']?></p>
              <h3 class="item_text">
                <?php   mb_internal_encoding("UTF-8");
                echo mb_strimwidth($item['name'], 0, 21, "...");  ?>
              </h3>
              <p class="item_text"><?=$item['price']?> uah</p>
            </a>
          </article>
      <?php
          }
        }else{
          echo "В данной категории пока нет товаров";
        }
      ?> 
    </div>
      
	</main>

	<?php include"/includes/footer.php";?>

</div>
</body>
</html>