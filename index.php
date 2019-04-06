<?php 
require_once("fonksiyonlar.php"); 
include("header.php");
$id = $_GET["id"];
	if (isset($id) and gecerliMi($id)){
		$idGecerli = true;
	}else{
		$idGecerli = false;
	}
?>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item">
						<a class="nav-link" href="#">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Privacy</a>
					</li>
				</ul>
				<a class="navbar-brand" href="https://teknojen.net">Teknojen</a>
			</div>
		</nav>
		<div class="container">
			<div class="col">
				<h2 class="text-center" style="margin-top:1%">Full Size Instagram Profile Picture Viewer</h2>
			</div>
				<div class="row">
				  <div class="col">
					  <div class="col text-center" style="margin-top: 1%">
							<div class="input-group input-group-md">
							  <div class="input-group-prepend"><span class="input-group-text">&nbsp;URL / ID</span> </div>
							  <?php 
								if ($idGecerli){
									echo '<input id="instaID" type="text" value="'.$id.'" class="form-control" placeholder="Etc. realbarbarapalvin">';
								}else{
									echo '<input id="instaID" type="text" class="form-control" placeholder="Etc. realbarbarapalvin">';
								}
							  ?>
							  
							</div>
						<button style="margin-top:2%" id="doIt" type="button" class="btn btn-primary">Get Profile Picture</button>
					</div>
                  </div>
				</div>
				<hr>
				<div id="sonucNedir" class="container text-center" style="margin-top: 1%"></div>
			<div class="card">
			  <div class="card-header">
				  <h3>How To Get Full Size Instagram Profile Picture?</h3>
			  </div>
			  <div class="card-body">
				<blockquote class="blockquote mb-0">
					<p><b>Step #1</b>: Copy the URL of the Instagram profile which would you like to see bigger profile picture.</p>
				  	<p>You can also use the username of the Instagram profile. Example: the URL of the profile is https://www.instagram.com/<b>realbarbarapalvin</b> and username is <b>realbarbarapalvin</b>.</p>
					<p><b>Step #2</b>: Put the URL or username of the Instagram profile into the input and then press the "Get Profile Picture" button, you can also hit enter.</p>
				</blockquote>
			  </div>
			</div>
			<hr>
			<div class="card">
			  <div class="card-header">
				  <h3>Downloading Full Size Instagram Profile Image</h3>
			  </div>
			  <div class="card-body">
				<blockquote class="blockquote mb-0">
					<p><b>Step #1</b>: Follow steps above and get someones bigger profile picture.</p>
					<p><b>Step #2</b>: Saving full size profile image:</p>
					<p>On computer: Right click to "See Full Image" then click "Save As" and save image to your computer.</p>
					<p>On smartphones: Long press to profile image, press "Download Image" and save image to your smartphone.</p>
				</blockquote>
			  </div>
			</div>
			<hr>
			<div class="container text-center my-3">
				<h3>Recommended Instagrammers</h3>
				<div id="slaytGelecek">
				</div>
			</div>
		</div>

		<script>
		function getir(uid){
			document.getElementById("sonucNedir").innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';
			if (!uid){
				uid = document.getElementById("instaID").value;
			}
			$.get("get.php",{ne : "html", id : uid},
				function(data) {
				   document.getElementById("sonucNedir").innerHTML = data
				}
			);
		}
		<?php
			if ($idGecerli == true){
				echo 'getir("'.$id.'");
				';
			}
		 ?>
		function slaytGetir(){
			document.getElementById("slaytGelecek").innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';
			$.get("get.php",{ne : "slayt"},
				function(data) {
				   document.getElementById("slaytGelecek").innerHTML = data
				}
			);	
		}
		slaytGetir();

		$("#doIt").click(function(){
			getir();
		});
		window.onkeyup = function(e) {
		   var key = e.keyCode ? e.keyCode : e.which;

		   if (key == 13) {
			   getir();
		   }
		}
		</script>

		<footer class="page-footer font-small blue">
		  <div class="footer-copyright text-center py-3"><a>This site has no association with Instagram.<br>We do not have any copyright content on our servers, all instagram photos you download are downloaded directly from the their servers.<br>If you have any questions or suggestions please send an email to hakan@teknojen.net.</a></div>
		  <div class="footer-copyright text-center py-3">&copy; 2019 Copyright:
			<a href="https://teknojen.net">Teknojen</a>
		  </div>
		</footer>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>