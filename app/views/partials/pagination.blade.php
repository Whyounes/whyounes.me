<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

@if ($paginator->getLastPage() > 1)
	<ul class="pager">
		{{ $presenter->getNext('&laquo; Older') }}
		{{ $presenter->getPrevious('Newer &raquo;') }}
	</ul>
@endif
