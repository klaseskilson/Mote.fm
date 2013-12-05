

			<footer id="footer">
				<div class="container">

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
