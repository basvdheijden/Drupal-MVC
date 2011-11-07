Ik ben gethemed.. yay!!!!
<?php echo count($view['view']->content)?>

<?php var_dump($view['view']->content);?>
<?php foreach ($view['view']->content as $k => $row ): ?>
  <br />
  <strong><?php var_dump($row)?></strong>
<?  endforeach;?>
  
