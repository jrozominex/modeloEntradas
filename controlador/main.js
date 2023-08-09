window.onload = function(){
	document.querySelector('.boton').addEventListener('click', function(){
		document.querySelector('.container').classList.toggle('invisible');
		document.querySelector('.contenido').classList.toggle('visible');
		this.classList.toggle('mif-chevron-right');
	});
}