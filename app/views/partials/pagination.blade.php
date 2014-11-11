<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

@if ($paginator->getLastPage() > 1)
	<ul class="pager">
		{{ $presenter->getNext('&larr; Older') }}
		{{ $presenter->getPrevious('Newer &rarr;') }}
	</ul>
@endif
