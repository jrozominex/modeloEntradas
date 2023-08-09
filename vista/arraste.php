<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		#cont0 { width: 80%; height: 200px;  }
		#cont1 { width: 50%; height: 200px; background-color:#ccffff; float: left; overflow: auto; }
		#cont2 { width: 50%; height: 200px; background-color:#ffccff; float: left; overflow: auto; }
	</style>
	<script type="text/javascript">
		function evdragstart(ev) {
		    ev.dataTransfer.setData("text",ev.target.id);	 
		}
		function evdragover (ev) {
		    ev.preventDefault();
		}
		function evdrop(ev,el) {
		    ev.stopPropagation();
		    ev.preventDefault();
		    data=ev.dataTransfer.getData("text");
		    ev.target.appendChild(document.getElementById(data));
		}
	</script>
</head>
<body>
	<div id="cont0">
	<div id="cont1" ondragover="evdragover(event)" ondrop="evdrop(event)">
	  <p draggable="true" ondragstart="evdragstart(event)" id="e1">
	       Elemento arrastrable</p>
	  <h5 draggable="true" ondragstart="evdragstart(event)" id="e2">
	       Otro elemento arrastrable</h5>
	  <ul draggable="true" ondragstart="evdragstart(event)" id="e3">
	     <li>Primer elemento</li>
	     <li>Segundo elemento</li>
	  </ul>
	</div> 
	<div id="cont2" ondragover="evdragover(event)" ondrop="evdrop(event,this)">
	  <img src="objetos/espana.gif" alt="españa" 
	      draggable="true" ondragstart="evdragstart(event)" id="e4"/>
	  <img src="objetos/europa.gif" alt="europa" 
	      draggable="true" ondragstart="evdragstart(event)" id="e5"/>
	  <img src="objetos/italia.gif" alt="italia" 
	      draggable="true" ondragstart="evdragstart(event)" id="e6"/>
	</div> 
	</div>
</body>
</html>