<?php

/**
 * Base Model Class
 *
 * Provides a reusable PDO database connection for all models
 * in the Alzikrayat application.
 *
 * @return void
 * @throws PDOException If the database connection cannot be established.
 */
class Model
{
    /**
     * PDO database connection.
     *
     * @var PDO
     */
    protected PDO $db;

    /**
     * Creates a model and loads the database connection.
     *
     * The database configuration file creates the PDO connection.
     * The connection is then assigned to this model's database property.
     *
     * @return void
     * @throws PDOException If the database connection cannot be established.
     */
    public function __construct()
    {
        require __DIR__ . '/../config/database.php';

        $this->db = $pdo;
    }
}