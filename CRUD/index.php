<?php

	/******* START recuperation de la liste des base de donnée *******/
		// CONNEXION BDD
		$pdo = new PDO('mysql:host=localhost;dbname=Mike', 'root', '', array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		));

		// Requete SQL
		$resultat = $pdo->prepare("SHOW DATABASES");
		$resultat->execute();

		// Trie de la requete
		$dataBase = $resultat->fetchall(PDO::FETCH_ASSOC);
	/******* END *******/

?>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="contenu">
			<form>
				<select id="databaseSelect">
					<?php
						foreach($dataBase as $value)
							echo "<option value='".$value['Database']."'>".$value['Database']."</option>";
					?>
				</select>
				<fieldset>
					<legend>Requete</legend>
					<textarea name="sql" id="sql" rows="4" cols="50">SELECT * FROM utilisateurs</textarea>
					<br/>
					<input type="submit" value="Envoyez" />
				</fieldset>
			</form>
		</div>
		<div id="mike">
		</div>
		<div>
			<p id="message"></p>
		</div>
		<script>
			$(function() { // document ready en Jquery
				$( "input" ).click(function(e) { // Evenement Jquery - Evenement qui se declanche au click d'un balise "input" - variable e stocke l'evenement

					// Annulation de l'actualisation de la page'
					e.preventDefault();


					// Console du meillieur Prenom au monde. PS: Mike > Vincent
					console.log("Mike")
					
					// Récuperation de la valeur de notre textarea.
					var myRequest = $("#sql").val(); 

					var dataBase = $("#databaseSelect").val();

					// Requete Ajax - Envoi des information du formulaire vers un autre page de traitement.
					var request = $.ajax({
						url: "read2.php", // Page de la requete
						method: "POST", // Methode de la requete
						data: {requet : myRequest, Mike: dataBase} // Data envoyer à la page
					});
					
					request.done(function( msg ) { // Success


						console.log(msg); // Affichage dans la console avant la conversion en un Object - msg est une String

						msg = JSON.parse(msg); // Conversion Json en Object Javascript

						console.info(msg); // Affichage dans la console part la conversion en un Object - msg est une Object Javascript

						if(msg.erreur == false)
						{
							$( "#mike" ).html( msg.message ); // Mise à jour du contenu de la div qui a pour id "Mike"
							$( "#requet" ).html( myRequest ); // Mise à jour du contenu de la span qui a pour id "requet" generer dans le tableau envoyer par le php

							
							$("#message").text("Voici les resultats de votre requete.");
							$("#message").css( "background-color", "green" );

						}else
						{
							$("#message").text(msg.message);
							$("#message").css( "background-color", "red" );
						}
					});
					
					request.fail(function( jqXHR, textStatus ) {
						alert( "Request failed: " + textStatus ); // En cas d'error de communication avec le serveur ou de code erreur
					});
				});
			});
		</script>
	<body>
<html>