<?php

/**
 * User Model
 *
 * Handles database operations related to application users.
 */
class User extends Model
{
    /**
     * Finds a user by email address.
     *
     * @param string $email The user's email address.
     * @return array<string, mixed>|null The user record or null if not found.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM Users WHERE email = :email LIMIT 1';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => $email
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    /**
     * Finds a user by ID.
     *
     * @param int $id The user's ID.
     * @return array<string, mixed>|null The user record or null if not found.
     */
    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM Users WHERE id = :id LIMIT 1';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    /**
     * Creates a new user.
     *
     * @param array<string, mixed> $data User data to insert.
     * @return int The ID of the newly created user.
     */
    public function create(array $data): int
    {
        $sql = '
            INSERT INTO Users
            (
                first_name,
                last_name,
                email,
                password,
                location,
                description,
                occupation
            )
            VALUES
            (
                :first_name,
                :last_name,
                :email,
                :password,
                :location,
                :description,
                :occupation
            )
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'occupation' => $data['occupation'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Counts all registered users.
     *
     * @return int The total number of registered users.
     */
    public function countAll(): int
    {
        $sql = 'SELECT COUNT(*) FROM Users';

        $statement = $this->db->prepare($sql);

        $statement->execute();

        return (int) $statement->fetchColumn();
    }
}