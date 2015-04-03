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
			$("#file").change(handleFileSelect);
		},
		afterClose: function() {
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
	
	//Limpa o conteúdo da lista de thumbnails
	$("#list").text('');
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
			$("#list").append(span);
		};
		})(f);
		
		// Read in the image file as a data URL.
		reader.readAsDataURL(f);
	}
}