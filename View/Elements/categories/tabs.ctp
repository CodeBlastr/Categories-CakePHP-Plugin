
<div class="categoryItems" id="elementCategoryItems">
  <div class="indexContainer">
    <div class="indexRow" id="headingRow">
      <div class="indexCell columnHeading"></div>
    </div>
	<?php
	$CategoryHelper = $this -> Helpers -> load('Categories.Category');
	$data = $CategoryHelper -> loadData();
	$list = $CategoryHelper -> displayList();

	foreach ($list as $catId => $catName) :
		$catArray[] = $CategoryHelper -> displayItems($catId);
	endforeach;
	?>



<div class="tabbable">
  <ul class="nav nav-tabs">

<?php $i = 0; foreach ($catArray as $cat) : $i++;?>		
    <li><a href="#tab<?php echo $i ?>" data-toggle="tab"><?php echo $cat['Category']['name'] ?></a></li>
<?php endforeach; ?>
	
  </ul>
  <div class="tab-content">
<?php $i = 0; foreach ($catArray as $cat) : $i++;?>		
    <div class="tab-pane" id="tab<?php echo $i ?>">
      <?php
      if (isset($cat['Associated'])) {
      	//debug($cat['Associated']);
		  ?>
		 
<div id="myCarousel" class="carousel slide">
  <ol class="carousel-indicators">
  	<?php $i = 0; foreach ($cat['Associated'] as $slide) :  $i++; ?>
    <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>"></li>
    <?php endforeach; ?>
  </ol>
  <!-- Carousel items -->
  <div class="carousel-inner">
    <?php $i = 0; foreach ($cat['Associated'] as $slide) :  $i++; ?>
    <div class="item <?php if ($i===1) echo 'active';?>">
    	<a href="#">
    		<img src="/theme/Default/media/images/<?php echo $slide[0]['Media'][0]['filename'].'.'.$slide[0]['Media'][0]['extension'] ?>" style="max-width: 200px;"/>
    	</a>
    </div>
    <?php endforeach; ?>
  </div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>
		 
		  <?php
       } else {
       	echo 'no items found';
       }
      ?>
    </div>
<?php endforeach; ?>
  </div>
</div>
	
	<?php
	echo $CategoryHelper -> display($data);
	?>
	
  </div>
</div>
