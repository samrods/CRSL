<!--PHP to maintain errors in the system------>
<!--Errors get included at the top of forms -->

<?php  if (count($errors) > 0) : ?>
  <div class="error">
  	<?php foreach ($errors as $error) : ?>
  	  <p style="color:yellow;"><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>