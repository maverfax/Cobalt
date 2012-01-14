<html>
<body>

<h1><?php echo $post->title; ?></h1>
<p><?php echo $post->body; ?></p>

<h1>Comments</h1>

<?php foreach($post->comments as $comment): ?>
	<?php echo $comment->body; ?>
<?php endforeach; ?>

<h1>Add a Comment</h1>

<?php echo Form::open(); ?>
	<?php echo Form::password('comment', $form->value('comment')); ?>
	<?php echo Form::submit('Login'); ?>
<?php echo Form::close(); ?>

<p>Loaded in: <?php echo load_time(); ?>
</body>
</html>