<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<?php include (ROOT_PATH.'/admin/post_functions.php'); ?>

<?php
$things = getAllPosts();
?>

<title>Admin | Manage Posts</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Display records from DB-->
		<div class="table-div">

			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

			<?php if (empty($things)) : ?>
				<h1>No Posts in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Author</th>
						<th>Title</th>
                        <th>Views</th>
						<th colspan="5">Publish</th>
                        <th colspan="5">Edit</th>
                        <th colspan="5">Delete</th>
					</thead>
					<tbody>
						<?php foreach ($things as $key => $thing) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<?php echo $things['Author']; ?>, &nbsp;
									<?php echo $things['Title']; ?>
								</td>
								<td><?php echo $things['Views']; ?></td>
                                <td>
                                    <a class="fa fa-checkmark btn publish" href="posts.php?publish-posts=<?php echo $posts['id'] ?>"> 
                                    </a>
                                </td>
								<td>
									<a class="fa fa-pencil btn edit" href="posts.php?edit-posts=<?php echo $posts['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="posts.php?delete-posts=<?php echo $posts['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>