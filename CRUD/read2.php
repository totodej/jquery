<?php

	// Les requêtes HTTP de type Cross-site sont des requêtes pour des ressources localisées sur un domaine différent de celui à l'origine de la requête (plus d'info : https://developer.mozilla.org/fr/docs/HTTP/Access_control_CORS').
	header('Access-Control-Allow-Origin: *'); 

	$retour = array("erreur" => true); // Initialisation de la variable de retour

	// Verification de l'existance de nos POST
	if(isset($_POST["requet"]) && isset($_POST["Mike"])){

		// Verification du contenu de nos POST
		if(!empty($_POST["requet"]) && !empty($_POST["Mike"])){

			// CONNEXION BDD
			$pdo = new PDO('mysql:host=localhost;dbname='.$_POST["Mike"], 'root', '');

			// Requete SQL envoyer par notre utilisateur
			$resultat = $pdo->prepare($_POST["requet"]);
			if( $resultat->execute()){

				// Trie de la requete
				$utilisateurs = $resultat->fetchall(PDO::FETCH_ASSOC);

				// Creation de la varible tableau
				$tableau = "<div>
					<div>
						<p>Requet : <span id='requet'></span></p>
						<p>Nombre de lignées : <span id='lignees'>".$resultat->RowCount()."</span></p>
					</div>
				<div>
					<table>
						<tr>";

				// Meme syntaxe. Trie du PREMIER ET SEUL element.
				foreach ($utilisateurs[0] as $key => $value){
					$tableau .= '<th>'.$key.'</th>';
				}

				$tableau .= "</tr>";

				// Boucle pour parcourir chaque ligne de notre bdd
				foreach ($utilisateurs as $key => $value){
					$tableau .= "<tr>";

					// Boucle chaque colone de nos lignes
					foreach ($utilisateurs[$key] as $key => $value)
						$tableau .= "<td>".$value."</td>";

					$tableau .= "</tr>";
				}
				$tableau .= "</table>
					</div>
				</div>";
				$retour["erreur"] = false;
				$retour["message"] = $tableau;
			}else{
				$retour["message"] = $resultat->errorInfo()[2];
			}
		}else{
			$retour["message"] = "Parametre vide!";  // Gestion erreur if empty variable POST
		}
	}else{
		$retour["message"] = "Parametre manquant!";  // Gestion erreur if isset variable POST
	}

	echo json_encode($retour);