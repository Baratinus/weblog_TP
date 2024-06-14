<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/all_functions.php'); ?>
<?php include('includes/public/registration_login.php'); ?>

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
				
			<div class="post" style="margin-left: 0px;">
				<img class="post_image" 
					 src="<?= $post["image"] != "" ? 'static/images/' . $post["image"] : 'static/images/banner.jpg' ?>" 
					 alt="image de <?= $post['title'] ?>">
				<div class="category"><?php echo $post["topic"]; ?></div>

				<div class="post_info">
					<?php echo $post["title"]; ?> 
					<br>
					<span><?php echo $post["created_at"]; ?></span>
					<span class="read_more"><a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">Read more...</a></span>
				</div>
				
			</div>

			<?php } ?>

		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->