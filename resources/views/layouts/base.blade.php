<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="description" content="">
		<meta http-equiv="x-dns-prefetch-control" content="on">
		<title>华为知识库系统</title>
		<link rel="stylesheet" href="../css/fontawesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../js/vue/iview.css" />
		<link rel="stylesheet" href="../js/vue/iview-ext.css" />
		@yield('css')
	</head>
	<body>
		@yield('content')
	</body>
	<script src="js/seajs/sea.js"></script>
	<script src="js/vue/vue.min.js"></script>
	<script src="js/vue/iview.min.js"></script>
	<script src="js/mock/mock-min.js"></script>
	@yield('script')
</html>