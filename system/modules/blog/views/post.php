<html>
<body>

<h1><?php echo $post->title; ?></h1>
<p><?php echo $post->body; ?></p>

<h1>Comments</h1>

<?php foreach($comments as $comment): ?>
	<p><?php echo $comment->content; ?></p>
<?php endforeach; ?>

<h1>Add a Comment</h1>

<?php echo Form::open(); ?>
	<?php echo Form::textarea('content', $form->value('content')); ?>
	<?php echo Form::submit('Comment'); ?>
<?php echo Form::close(); ?>

<p>Loaded in: <?php echo load_time(); ?>
</body>
</html>