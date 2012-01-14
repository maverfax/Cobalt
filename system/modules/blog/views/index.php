<html>
<body>

<?php foreach($posts as $post): ?>
	<h1><?php echo $post->title; ?></h1>
	<p><?php echo $post->body; ?></p>
	<p>(<a href="<?php echo site_url('blog/post/' . $post->id); ?>"><?php echo $post->comments; ?> Comments</a>)</p>
<?php endforeach; ?>

<p>Loaded in: <?php echo load_time(); ?>
</body>
</html>