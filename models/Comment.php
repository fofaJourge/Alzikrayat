<?php

/**
 * Comment Model
 *
 * Handles database operations related to photo comments.
 */
class Comment extends Model
{
    /**
     * Retrieves all comments belonging to a specific photo.
     *
     * @param int $photoId The ID of the photo.
     * @return array<int, array<string, mixed>> The photo comments.
     */
    public function getByPhotoId(int $photoId): array
    {
        $sql = '
            SELECT
                Comments.id,
                Comments.photo_id,
                Comments.user_id,
                Comments.comment,
                Comments.date_time,
                Users.first_name,
                Users.last_name
            FROM Comments
            INNER JOIN Users
                ON Comments.user_id = Users.id
            WHERE Comments.photo_id = :photo_id
            ORDER BY Comments.date_time ASC, Comments.id ASC
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'photo_id' => $photoId
        ]);

        return $statement->fetchAll();
    }

    /**
     * Creates a new comment.
     *
     * @param array<string, mixed> $data Comment data to insert.
     * @return int The ID of the newly created comment.
     */
    public function create(array $data): int
    {
        $sql = '
            INSERT INTO Comments
            (
                photo_id,
                user_id,
                comment
            )
            VALUES
            (
                :photo_id,
                :user_id,
                :comment
            )
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'photo_id' => $data['photo_id'],
            'user_id' => $data['user_id'],
            'comment' => $data['comment']
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Counts all comments in the application.
     *
     * @return int The total number of comments.
     */
    public function countAll(): int
    {
        $sql = 'SELECT COUNT(*) FROM Comments';

        $statement = $this->db->prepare($sql);

        $statement->execute();

        return (int) $statement->fetchColumn();
    }
}