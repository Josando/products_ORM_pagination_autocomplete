<div id='main'>
    <br><br>

        <?php
        if (isset($arrData) && !empty($arrData)) {
            //https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_de_estado_HTTP
            ?>
            <h1>ERROR <?php echo $arrData['code'] ?> - <?php echo $arrData['text'] ?></h1>
            <?php
        }
        ?>


    <div id='content'>
        <h2>The following error occurred:</h2>
        <p>The requested URL was not found on this server.</p>
        <P>Please check the URL or contact the <!--WEBMASTER//-->webmaster<!--WEBMASTER//-->.</p>
    </div>

</div>

<!-- Tweet -->
		<div id="tweet">
			<div class="container">
				<section>
					<blockquote>&ldquo;In posuere eleifend odio. Quisque semper augue mattis wisi. Maecenas ligula. Pellentesque viverra vulputate enim. Aliquam erat volutpat.&rdquo;</blockquote>
				</section>
			</div>
		</div>
	<!-- /Tweet -->
