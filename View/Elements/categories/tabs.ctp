<?php
$CategoryHelper = $this->Helpers->load('Categories.Category');
$data = $CategoryHelper->loadData();
$list = $CategoryHelper->displayList();

foreach ($list as $catId => $catName) {
	$catArray[] = $CategoryHelper->displayItems($catId);
}
?>

<div class="card-builder-con c-builder">
	<section id="tabs">
		
		<div class="bs-docs-example">
			<div class="carousel slide" id="myCarousel">
				<div class="carousel-cat">
					<ul class="nav nav-tabs" id="myTabContent">
						<?php $i = 0; foreach ($catArray as $cat) : $i++; ?>
						<li>
							<a data-toggle="tab" href="#tab<?php echo $i ?>"><?php echo $cat['Category']['name'] ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="tab-content" id="myTabContent1">

<?php $i = 0; foreach ($catArray as $cat) : $i++;?>	

			<div class="tab-pane fade" id="tab<?php echo $i ?>">
				
				<?php if (isset($cat['Associated'])) : ?>
				<?php
				$iSlide = 0;
				foreach ($cat['Associated']['MediaGallery'] as $slide) {
					$slides[] = $slide;
					$iSlide++;
					if ($iSlide === 4 || $iSlide === count($cat['Associated']['MediaGallery'])) {
						$sets[] = $slides;
						$slides = array();
						$iSlide = 0;
					}
				}
				?>
				
				<div class="carousel slide" id="myCarousel<?php echo $i ?>">
					<div class="carousel-inner">
						<?php $iSet = 0; foreach ($sets as $set) : ?>
						<div class="item <?php if ($iSet === 0) echo 'active' ?>">
							<ul>
								<?php foreach ($set as $slide) : ?>
								<li>
									<img src="/theme/Default/media/images/<?php echo $slide['Media'][0]['filename'].'.'.$slide['Media'][0]['extension'] ?>"  style="max-width: 290px;" />
									<a href="/media/mediaGalleries/canvas/<?php echo $slide['MediaGallery']['id']?>/<?php echo $slide['Media'][0]['id']?>" class="btn btn-warning">Personalize</a>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php $iSet++; endforeach; ?>
					</div>
					<?php if (count($sets) > 4) : ?>
					<a class="left carousel-control" data-slide="prev" href="#myCarousel<?php echo $i ?>">&nbsp;</a><a class="right carousel-control" data-slide="next" href="#myCarousel<?php echo $i ?>">&nbsp;</a>
					<?php endif; ?>
				</div>
				
				<?php else : ?>
					<p>no items in this category yet</p>
				<?php endif; ?>
				
			</div>

<?php endforeach; ?>

		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.carousel').carousel('pause');
	}); 
</script>
