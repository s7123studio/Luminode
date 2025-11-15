<?php

namespace Luminode\Core;

use Luminode\Core\Database;
use Exception;
use PDO;

class Auth
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function attempt(string $username, string $password): bool
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        try {
            $user = $this->db->query("SELECT * FROM users WHERE username = ?", [$username])->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Rehash password if needed
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->query("UPDATE users SET password = ? WHERE id = ?", [$newHash, $user['id']]);
                }
                
                $this->login($user);
                return true;
            }
        } catch (Exception $e) {
            // In a real app, you would log the exception
            return false;
        }

        return false;
    }

    /**
     * Log the given user in.
     *
     * @param array $user
     * @return void
     */
    public function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
    }

    /**
     * Check if a user is authenticated.
     *
     * @return bool
     */
    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return array|null
     */
    public function user(): ?array
    {
        if (!$this->check()) {
            return null;
        }
        
        // Fetch fresh user data from DB to avoid stale session data
        return $this->db->query("SELECT * FROM users WHERE id = ?", [$this->id()])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get the ID of the currently authenticated user.
     *
     * @return int|null
     */
    public function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}