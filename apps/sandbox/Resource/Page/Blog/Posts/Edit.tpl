<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>BEAR.Sunday blog</title>
<link href="/assets/css/bootstrap.css" rel="stylesheet">
<link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="/">Home</a> <span class="divider">/</span></li>
			<li><a href="/blog/posts">Blog</a> <span class="divider">/</span></li>
			<li class="active">Edit Post</li>
		</ul>

		<form action="/blog/posts/edit" method="POST">
			<input name="X-HTTP-Method-Override" type="hidden" value="PUT" />
			<input name="id" type="hidden" value="{$id}" />
			<div class="control-group {if $errors.title}error{/if}">
				<label class="control-label" for="title">Title</label>
				<div class="controls">
					<input type="text" id="title" name="title" value="{$submit.title}">
					<p class="help-inline">{$errors.title}</p>
				</div>
			</div>
			<div class="control-group {if $errors.body}error{/if}">
				<label>Body</label>
				<textarea name="body" rows="10" cols="40">{$submit.body}</textarea>
				<p class="help-inline">{$errors.body}</p>
			</div>
			<input type="submit" value="送信">
		</form>
	</div>
</body>
</html>