<?php
namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    protected string $table = 'usuarios';

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Devuelve el usuario (sin el hash) si email y contraseña son correctos; null si no.
     */
    public function verificarCredenciales(string $email, string $password): ?array
    {
        $user = $this->buscarPorEmail($email);
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return null;
        }
        unset($user['password_hash']);
        return $user;
    }
}
