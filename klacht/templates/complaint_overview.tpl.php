<h1>Overview of complaints</h1>

<?php foreach ($view->content as $row ): ?>
  <br />
  <strong><?php print l($row->title->value(), $row->getUri()); ?></strong>
<?php endforeach; ?>