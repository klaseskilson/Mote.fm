<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>
					Profile <small><?php echo $user['name'] ?></small>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<?php 
					$gravatarMd5 = md5(strtolower($user['email']));
					echo("<img src='http://www.gravatar.com/avatar/$gravatarMd5?s=200' width=200 alt=''/>");
				?>
				<p>Name: <?php echo $user['name'] ?></p>
				<p>Email: <?php echo $user['email'] ?></p>
			</div>
			<div class="col-sm-4">
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</div>
		</div>
	</div>
</main>
