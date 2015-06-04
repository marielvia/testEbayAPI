@extends('app')

@section('content')
<div class="container">
	@if ($result['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'] > 0)
		{{--*/ $items = $result['findItemsAdvancedResponse'][0]['searchResult'][0]['item'] /*--}}
		@foreach ($items as $item)
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-default">
						<div class="panel-heading">Home</div>

						<div class="panel-body">
							<div class="quote">agagagagaag</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@else 
		No results found
	@endif
</div>
@endsection
