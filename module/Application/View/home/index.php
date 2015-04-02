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
		'<br/><output id="list"></output>'+
		'<br/> <input type="submit" class="button" value="Enviar" style="margin-top:15px"> </form>',
		afterOpen: function($vexContent) {
			document.getElementById('file').addEventListener('change', handleFileSelect, false);
			// return $vexContent.append($el);
		},
		afterClose: function() {
			// return console.log('vexClose');
		},
		showCloseButton: true,
		overlayClosesOnClicke: false
	});
});

// Check for the various File API support.
if (window.File && window.FileReader && window.FileList && window.Blob) {
  // Great success! All the File APIs are supported.
} else {
  alert('The File APIs are not fully supported in this browser.');
}

function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object
	
	document.getElementById('list').innerHTML = '';
	// Loop through the FileList and render image files as thumbnails.
	for (var i = 0, f; f = files[i]; i++) {
	
	  // Only process image files.
	  if (!f.type.match('image.*')) {
	    continue;
	  }
	
	  var reader = new FileReader();
	
	  // Closure to capture the file information.
	  reader.onload = (function(theFile) {
	    return function(e) {
	      // Render thumbnail.
	      var span = document.createElement('span');
	      span.innerHTML = ['<img class="thumb" src="', e.target.result,
	                        '" title="', escape(theFile.name), '"/>'].join('');
	      document.getElementById('list').insertBefore(span, null);
	    };
	  })(f);
	
	  // Read in the image file as a data URL.
	  reader.readAsDataURL(f);
	}
}
</script>
<footer>
	<p>PET Computação UFPR<br/>
	&copy; 2015
	</p>
</footer>