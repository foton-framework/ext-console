<? foreach ($data as $row): ?>
	<a href="<?=$row->full_link ?>"><?=$row->title ?></a>
	<hr>
<? endforeach ?>