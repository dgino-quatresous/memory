<?php
// Chargement manuel des fichiers nécessaires au fonctionnement de l'application
// Vérifie la présence de l'autoload généré par Composer
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
	require_once $autoload;

	// Charge les variables d'environnement si la librairie dotenv est disponible
	if (class_exists(\Dotenv\Dotenv::class)) {
		$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
		$dotenv->safeLoad();
	}
} else {
	http_response_code(500);
	echo '<h1>Erreur d\'installation</h1>';
	echo '<p>Le fichier <code>vendor/autoload.php</code> est introuvable.</p>';
	echo '<p>Pour corriger ceci, exécutez dans le répertoire du projet :</p>';
	echo '<pre>composer install</pre>';
	echo '<p>Si <code>composer</code> n\'est pas installé, installez-le ou utilisez l\'outil fourni par Laragon.</p>';
	exit;
}
// Importation des classes avec namespaces pour éviter les conflits de noms
use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ArticleController;

// Initialisation du routeur
$router = new Router();

// Définition des routes de l'application
// La route "/" pointe vers la méthode "index" du contrôleur HomeController
$router->get('/', 'App\\Controllers\\HomeController@index');

$router->get('/articles', 'App\\Controllers\\ArticleController@index');
$router->get('/articles', 'App\\Controllers\\ArticleController@index');

// Routes pour le jeu Memory
$router->get('/game', 'App\\Controllers\\GameController@index');
$router->get('/game/play', 'App\\Controllers\\GameController@play');
$router->get('/game/save', 'App\\Controllers\\GameController@save');
$router->get('/game/leaderboard', 'App\\Controllers\\GameController@leaderboard');
$router->get('/game/profile', 'App\\Controllers\\GameController@profile');

// Exécution du routeur :
// On analyse l'URI et la méthode HTTP pour appeler le contrôleur et la méthode correspondants
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
