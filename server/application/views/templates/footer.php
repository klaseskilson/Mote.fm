

			<footer id="footer">
				<div class="container">
					<div class="row">
						<div class="col-sm-6">
							<h3>
								Mote.fm
							</h3>
							<p>
								Mote.fm is an awesome way to democratize Spotify's play que.
							</p>
						</div>
						<div class="col-sm-3">
							<h3>
								Support
							</h3>
							<p>
								Need help? Hit us up on <a href="https://twitter.com/motefm" target="_blank">Twitter</a>
								or <a href="https://facebook.com/motefm" target="_blank">Facebook</a>, and we'll answer every
								question.
							</p>
						</div>
						<div class="col-sm-3">
							<h3>
								Follow Mote.fm
							</h3>
							<ul class="list-unstyled">
								<li>
									<a href="https://facebook.com/motefm" target="_blank">Facebook</a>
								</li>
								<li>
									<a href="https://twitter.com/motefm" target="_blank">Twitter</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer><!-- end #footer -->
		</div><!-- end #container -->
		<!-- JavaScript libraries -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script>
		<script>
		window.jQuery || document.write('<script src=\"<?php echo base_url(); ?>web/js/jquery.min.js\">\x3C/script>');
		</script>
		<?php echo isset($ajax) ? '<script src="web/js/hathor.js"></script>': ''; ?>

		<!-- Javascripts -->
		<script type="text/javascript">
		var BASE_URL = '<?php echo base_url(); ?>';
		</script>
		<script src="<?php echo base_url(); ?>web/js/main.js"></script>
		<?php
		// send JS files to be loaded in the $extra_js array
		if(isset($extra_js) && is_array($extra_js))
		{
			foreach ($extra_js as $js)
			{
				echo '<script src="'.base_url().'web/js/'.$js.'"></script>';
			}
		}
		?>

	</body>
</html>
