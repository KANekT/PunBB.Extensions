<?php if (!empty($errors)) :?>
		<div class="ct-box error-box">
			<h2 class="warn hn"><?php echo $head ?></h2>
			<ul class="error-list">
<?php foreach ($errors as $cur_error) : ?>			
				<li class="warn"><span><?php echo $cur_error ?><a></a></span></li>
<?php endforeach; ?>
			</ul>
		</div>		
<?php endif ?>	