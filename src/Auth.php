<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:18:38
 */

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
     * 尝试验证用户身份
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool 是否验证成功
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
     * 登录指定用户
     *
     * @param array $user 用户数据
     * @return void
     */
    public function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
    }

    /**
     * 检查用户是否已认证
     *
     * @return bool 是否已认证
     */
    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * 获取当前已认证的用户
     *
     * @return array|null 用户数据，未认证则返回null
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
     * 获取当前已认证用户的ID
     *
     * @return int|null 用户ID，未认证则返回null
     */
    public function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * 登出当前用户
     *
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}