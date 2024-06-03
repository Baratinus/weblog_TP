<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/all_functions.php'); ?>
<title>MyWebSite | Home </title>

</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Banner -->
		<?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
		<!-- // Banner -->

		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
			<h2 class="content-title">Recent Articles</h2>
			<hr>

			<?php foreach (getPublishedPosts() as $post) { ?>
				
			<div>
				<img src="<?php echo 'static/images/' . $post["image"]; ?>" alt="">
				<div><?php echo $post["title"]; ?></div>
				<div><?php echo $post["created_at"]; ?></div>
				<div><a href="<?php echo 'coucou'; ?>">Read more...</a></div>
			</div>

			<?php } ?>

		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->