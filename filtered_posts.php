<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/all_functions.php'); ?>

<?php $topic = $_GET['topic']; ?>
<?php $topicname = getNameTopic($topic) ?>
<title><?php echo ($topicname)?> | MyWebSite</title>

</head>
<body>

<div class="container">

    <!-- Navbar -->
    <?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
    <!-- // Navbar -->

    <!-- content -->
		<div class="content">
			<h2 class="content-title"><?php echo $topicname ?> Articles</h2>
			<hr>
			<?php foreach(getPublishedPostsByTopic($topic) as $post) {?>
				
			<div class="post" style="margin-left: 0px;">
				<img class="post_image" src="<?php echo 'static/images/' . $post["image"]; ?>" alt="">
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


    <!-- post sidebar -->
    <div class="post-sidebar">
        <div class="card">
            <div class="card-header">
                <h2>Topics</h2>
            </div>
            <div class="card-content">
                <?php foreach (getAllTopics() as $topic) { ?>
                <a href="filtered_posts.php?topic=<?php echo $topic['id'] ?>"><?php echo $topic['name']; ?></a>
                <?php } ?>
            </div>
            
        </div>
    </div>
    <!-- // post sidebar -->
    </div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>
