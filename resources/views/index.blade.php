@extends('layouts.base')

@section('css')
<link rel="stylesheet" type="text/css" href="./css/index.css" />
@endsection
@section('content')
<section class="app">
	<section class="nav-bar">
		<div class="user-info"></div>
	</section>
	<section :class="['model-search',docList.length>0?'has-result':'']">
		@include('layouts.templates.search')
		<section class="result-list" v-if="docList.length>0">
			@include('layouts.templates.card-doc')
		</section>
	</section>
	@include('layouts.templates.del-comfirm')
	@include('layouts.templates.edit')
</section>
@endsection
@section('script')
<script src="./js/mock/mock-min.js"></script>
<script>seajs.use('./js/index.js', function(model) {});</script>
@endsection
