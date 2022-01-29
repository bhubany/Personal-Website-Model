<?php  if (count($errors) > 0) : ?>
  <div class="alert alert-danger text-center" role="alert">
  	<?php foreach ($errors as $error) : ?>
  	  <i><?php echo $error."<br>" ?></i>
  	<?php endforeach ?>
  </div>
<?php  endif ?>