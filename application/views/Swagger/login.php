<!DOCTYPE html>
<html lang="<?php echo $system['lang']?>">
	<head>
		<meta charset="UTF-8"> 
		<meta name="robots" content="noindex,nofollow">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="shortcut icon" href="/assets/img/favicon.ico">
		<title><?php echo $meta['title'];?></title>
		<!-- JQuery -->
		<script src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js?"></script>
		<!-- JQuery UI -->
		<script src="/assets/plugins/jQuery-UI/1.11.4/jquery-ui.min.js?"></script>
		<!-- validate -->
		<script src="/assets/plugins/jQuery-validate/1.15.0/jquery.validate.min.js?"></script>
		<script type="text/javascript">
		$(function(){
			$("#login_form").validate({
				errorPlacement: function(error, element) {
					element.css('border', '2px solid #ff0000');
				}
			});
		});
		</script>
		<!-- JQuery UI -->
		<link rel="stylesheet" href="/assets/plugins/jQuery-UI/1.11.4/jquery-ui.min.css?">
		<!-- login style -->
		<link rel="stylesheet" href="/assets/css/<?php echo $system['swagger_css'];?>/login.css?">
	</head>
	<body>
		<div class="wrapper">
			<div class="container">
				<h1>Welcome</h1>
				<form id="login_form" class="form" method="post" action="<?php echo $system['form_action'];?>">
					<input type="text" name="code" class="required" placeholder="GoogleAuthenticator" />
					<button type="submit" id="login-button">Verify</button>
				</form>
			</div>
			<ul class="bg-bubbles">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
	</body>
</html>