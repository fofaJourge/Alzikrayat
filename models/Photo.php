<?php

/**
 * Photo Model
 *
 * Handles database operations related to photos
 * in the Alzikrayat application.
 *
 * The model communicates directly with the Photos table
 * and keeps database logic separate from controllers and views.
 */
class Photo extends Model
{
    /**
     * Retrieves all photos with their author's name.
     *
     * Photos are returned from newest to oldest.
     *
     * @return array<int, array<string, mixed>>
     *         A list of photos and their author information.
     */
    public function getAll(): array
    {
        $sql = '
            SELECT
                Photos.id,
                Photos.user_id,
                Photos.file_name,
                Photos.title,
                Photos.description,
                Photos.date_time,
                Users.first_name,
                Users.last_name
            FROM Photos
            INNER JOIN Users
                ON Photos.user_id = Users.id
            ORDER BY Photos.date_time DESC
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Finds a photo by its ID and includes its author's information.
     *
     * @param int $id The photo ID.
     * @return array<string, mixed>|null
     *         Photo data or null when the photo does not exist.
     */
    public function findById(int $id): ?array
    {
        $sql = '
            SELECT
                Photos.id,
                Photos.user_id,
                Photos.file_name,
                Photos.title,
                Photos.description,
                Photos.date_time,
                Users.first_name,
                Users.last_name
            FROM Photos
            INNER JOIN Users
                ON Photos.user_id = Users.id
            WHERE Photos.id = :id
            LIMIT 1
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        $photo = $statement->fetch();

        return $photo ?: null;
    }

    /**
     * Finds a photo that belongs to a specific user.
     *
     * This method is used before deletion so that the controller
     * can obtain the physical filename while enforcing ownership
     * at the database level.
     *
     * @param int $photoId The photo ID.
     * @param int $userId The ID of the requesting user.
     * @return array<string, mixed>|null
     *         Owned photo data or null when it does not exist
     *         or does not belong to the user.
     */
    public function findOwnedPhoto(
        int $photoId,
        int $userId
    ): ?array {
        $sql = '
            SELECT
                id,
                user_id,
                file_name,
                title,
                description,
                date_time
            FROM Photos
            WHERE id = :photo_id
              AND user_id = :user_id
            LIMIT 1
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'photo_id' => $photoId,
            'user_id' => $userId
        ]);

        $photo = $statement->fetch();

        return $photo ?: null;
    }

    /**
     * Creates a new photo record in the database.
     *
     * The physical image file must already have been safely
     * uploaded to the server before this method is called.
     *
     * @param array<string, mixed> $data Photo information.
     * @return int ID of the newly created photo.
     */
    public function create(array $data): int
    {
        $sql = '
            INSERT INTO Photos
            (
                user_id,
                file_name,
                title,
                description
            )
            VALUES
            (
                :user_id,
                :file_name,
                :title,
                :description
            )
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'user_id' => $data['user_id'],
            'file_name' => $data['file_name'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Deletes a photo belonging to a specific user.
     *
     * The ownership condition is enforced directly in the SQL query.
     * This provides an additional server-side protection against
     * deleting another user's photo.
     *
     * @param int $photoId The ID of the photo to delete.
     * @param int $userId The ID of the user requesting deletion.
     * @return bool True when a photo was deleted, otherwise false.
     */
    public function deleteOwnedPhoto(int $photoId, int $userId): bool
    {
        $sql = '
            DELETE FROM Photos
            WHERE id = :photo_id
              AND user_id = :user_id
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'photo_id' => $photoId,
            'user_id' => $userId
        ]);

        return $statement->rowCount() > 0;
    }

    /**
     * Counts the total number of photos.
     *
     * This method will later be used by the homepage statistics.
     *
     * @return int Total number of photos.
     */
    public function countAll(): int
    {
        $sql = 'SELECT COUNT(*) FROM Photos';

        $statement = $this->db->prepare($sql);

        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    /**
     * Counts the number of photos uploaded by a specific user.
     *
     * @param int $userId The user ID.
     * @return int Number of photos uploaded by the user.
     */
    public function countByUserId(int $userId): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM Photos
            WHERE user_id = :user_id
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'user_id' => $userId
        ]);

        return (int) $statement->fetchColumn();
    }
}