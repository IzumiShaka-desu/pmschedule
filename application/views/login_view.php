<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Schedule Mnagement System Login</title>
	<meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
	<meta name="author" content="pixelcave">
	<meta name="robots" content="noindex, nofollow">

	<!-- Open Graph Meta -->
	<meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
	<meta property="og:site_name" content="Codebase">
	<meta property="og:description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
	<meta property="og:type" content="website">
	<meta property="og:url" content="">
	<meta property="og:image" content="">

	<!-- Icons -->
	<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
	<link rel="shortcut icon" href="assets/media/favicons/favicon.png">
	<link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
	<!-- END Icons -->

	<!-- Stylesheets -->
	<link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.css">

	<!-- Fonts and Codebase framework -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
	<link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">

</head>

<body>
	<!-- create standard login page bootstrap username and password -->
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Login</h3>
					</div>
					<div class="card-body">
						<?php if ($this->session->flashdata('error')) : ?>
							<div class="alert alert-danger" role="alert">
								<?php echo $this->session->flashdata('error'); ?>
							</div>
						<?php endif; ?>
						<form action="<?php echo base_url('login/validate'); ?>" method="post">
							<div class="form-group
							<?php echo (form_error('username') != '') ? 'has-error' : ''; ?>">
								<label for="username">Username</label>
								<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo set_value('username'); ?>">
								<?php echo form_error('username'); ?>
							</div>
							<div class="form-group
							<?php echo (form_error('password') != '') ? 'has-error' : ''; ?>">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Password">
								<?php echo form_error('password'); ?>
							</div>
							<button type="submit" class="btn btn-primary">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>



	<script src="assets/js/codebase.core.min.js"></script>
	<script src="assets/js/codebase.app.min.js"></script>


</body>

</html>
