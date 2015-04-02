<section class="content">
	<h1>Farol</h1>
	<form method="post" action="?module=application&controller=home&action=search">
		<input type="text" placeholder="O que você procura?" name="search" class="search-bar" autofocus required>
		<!-- Value do submit foi retirado daqui: http://fortawesome.github.io/Font-Awesome/cheatsheet/-->
		<input type="submit" class="search-button" value="&#xf002;">
	</form>
</section>
<p><a id="upload" class="upload-link">Faça o upload de uma prova/trabalho!</a></p>
<script>
$("#upload").click(function(){
	vex.open({
		//Modal para o upload de arquivos
		content: '<h1>Upload de arquivo</h1>'+
		'<form method="post" action="?module=application&controller=home&action=upload" enctype="multipart/form-data"> '+
		//Tamanho máximo para o arquivo: 15MB
		'<input type="hidden" name="MAX_FILE_SIZE" value="15728640">'+
		'<h6>Tamanho máximo de 15Mb por arquivo</h6>'+
		'<input type="file" name="file[]" id="file" multiple required> '+
		'<br/> <input type="submit" class="button" value="Enviar" style="margin-top:15px"> </form>',
		afterOpen: function($vexContent) {
			// return $vexContent.append($el);
		},
		afterClose: function() {
			// return console.log('vexClose');
		},
		showCloseButton: true
	});
});
</script>
<footer>
	<p>PET Computação UFPR<br/>
	&copy; 2015
	</p>
</footer>