<?php
	define("DEBUG", true);

	// Liste des dossier a exclure
	$exclude[] = '.';
	$exclude[] = '..';
	$exclude[] = '.git';

	// Prépare la réponse
	$reponse['path'] = '';
	$reponse['date'] = 0;

	function getDirContent($repertoire, $premierNiveau) {
		$contenuRepertoire = scandir($repertoire);

		global $exclude;
		global $reponse;

		$contenuRepertoire = array_diff($contenuRepertoire, $exclude);

		foreach ($contenuRepertoire as $key => $dirOrFile) {
			$path = realpath($repertoire . DIRECTORY_SEPARATOR . $dirOrFile);

			if(is_dir($path)) {
				if (DEBUG) {
					echo $path.' est un repertoire'.'<br />';
				}
				getDirContent($path, false);
			} else {
				if(!$premierNiveau) {
					if($reponse['date'] < filemtime($path)) {
						$reponse['date'] = filemtime($path);
						$reponse['path'] = $path;
					}
					if (DEBUG) {
						echo '&emsp;&emsp;' . $path . '&emsp;&emsp;' . filemtime($path) . '<br />';
					}
				}
			}
		}
	}

	// Lance la fonction qui est récursive (elle s'apelle elle même à l'interieur)
	getDirContent('./', true);

	if (DEBUG) {
		echo '<br /><br />';
		echo $reponse['path'] . ' ' . $reponse['date'];
		echo '<br /><br />';
		echo 'PATH du script ' . getcwd();
		echo '<br /><br />';
	}
	// Supprime le début du PATH par rapport à la position du script
	$constructionLien = str_replace(getcwd().'/', '', $reponse['path']);
	if (DEBUG) {
		echo $constructionLien;
		echo '<br /><br />';
	}
	$constructionLien = strtok($constructionLien, '/');
	if (DEBUG) {
		echo $constructionLien;
		echo '<br /><br />';
		echo '<a href="./' . $constructionLien . '">ICI</a>';
		echo '<br /><br />';
	echo 'Passer "define(\'DEBUG\', true);" à false pour être redirigé automatiquement.';

	} else {
		header('Location: '.$constructionLien);
	}
?>
