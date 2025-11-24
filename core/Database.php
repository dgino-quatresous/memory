<?php
namespace Core;

use PDO;
use PDOException;

/**
 * Classe Database
 * ----------------
 * Classe utilitaire qui centralise la connexion à la base de données via PDO.
 * Elle utilise le pattern Singleton afin de garantir une seule instance de connexion
 * partagée dans toute l'application.
 */
class Database
{
    /**
     * Instance PDO unique (Singleton)
     * @var PDO|null
     */
    private static ?PDO $pdo = null;

    /**
     * Méthode d'accès à l'instance PDO
     *
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        // Si aucune connexion n'existe encore, on l'initialise
        if (!self::$pdo) {
            // Récupère les paramètres depuis l'environnement, avec des valeurs par défaut
            $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
            $db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'mvc';
            $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';

            // Construction correcte du DSN (host, port, dbname)
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $db);

            try {
                // Création de l'instance PDO avec options
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur SQL
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats sous forme de tableau associatif
                ]);
            } catch (PDOException $e) {
                // En cas d'échec de connexion, logue l'erreur et affiche un message d'aide
                error_log('Database connection error: ' . $e->getMessage());
                http_response_code(500);
                echo '<h1>Erreur de connexion à la base de données</h1>';
                echo '<p>Impossible de se connecter à la base MySQL avec les paramètres actuels.</p>';
                echo '<p>Vérifiez vos variables d\'environnement <code>DB_HOST</code>, <code>DB_PORT</code>, <code>DB_NAME</code>, <code>DB_USER</code> et <code>DB_PASSWORD</code>.</p>';
                echo '<p>Pour un environnement Laragon local essayez par exemple :</p>';
                echo '<pre>DB_HOST=127.0.0.1\nDB_PORT=3306\nDB_NAME=mvc\nDB_USER=root\nDB_PASSWORD=</pre>';
                exit;
            }
        }

        // Retourne toujours la même instance PDO
        return self::$pdo;
    }
}
