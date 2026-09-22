<?php
class User {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function findByUsername(string $u): ?array {
        $s=$this->db->prepare("SELECT u.*,r.nama_role as role FROM users u JOIN roles r ON u.role_id=r.id WHERE u.username=? LIMIT 1");
        $s->execute([$u]); return $s->fetch()?:null;
    }
    public function verifyPassword(string $input, string $stored): bool {
        if ($stored === '') return false;
        return hash('sha256', $input) === $stored;
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT u.*,r.nama_role as role FROM users u JOIN roles r ON u.role_id=r.id WHERE u.id=? LIMIT 1");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function getAll(string $q='', string $roleId='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $p = [$like]; $w = "u.username LIKE ?";
        if ($roleId) { $w .= " AND u.role_id=?"; $p[] = $roleId; }
        $p[] = $pp; $p[] = $off;
        $s=$this->db->prepare("SELECT u.*,r.nama_role as role_nama FROM users u JOIN roles r ON u.role_id=r.id WHERE $w ORDER BY u.created_at DESC LIMIT ? OFFSET ?");
        $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $roleId=''): int {
        $like="%$q%"; $p = [$like]; $w = "username LIKE ?";
        if ($roleId) { $w .= " AND role_id=?"; $p[] = $roleId; }
        $s=$this->db->prepare("SELECT COUNT(*) FROM users WHERE $w"); $s->execute($p); return (int)$s->fetchColumn();
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO users (role_id,username,password,is_active) VALUES (?,?,?,?)");
        $s->execute([$d['role_id'],$d['username'],$d['password'],$d['is_active']??1]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE users SET role_id=?,username=?,is_active=? WHERE id=?");
        return $s->execute([$d['role_id'],$d['username'],$d['is_active'],$id]);
    }
    public function updatePassword(int $id, string $hash): bool {
        $s=$this->db->prepare("UPDATE users SET password=? WHERE id=?"); return $s->execute([$hash,$id]);
    }
    public function toggleStatus(int $id): bool {
        $s=$this->db->prepare("UPDATE users SET is_active=1-is_active WHERE id=?"); return $s->execute([$id]);
    }
    public function delete(int $id): bool {
        $s=$this->db->prepare("DELETE FROM users WHERE id=?"); return $s->execute([$id]);
    }
    public function isUsernameExist(string $u, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM users WHERE username=? AND id!=?");$s->execute([$u,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM users WHERE username=?");$s->execute([$u]);}
        return (int)$s->fetchColumn()>0;
    }
    public function getRoles(): array { return $this->db->query("SELECT * FROM roles ORDER BY id")->fetchAll(); }
    public function countByRole(string $role): int {
        $s=$this->db->prepare("SELECT COUNT(*) FROM users u JOIN roles r ON u.role_id=r.id WHERE r.nama_role=? AND u.is_active=1");
        $s->execute([$role]); return (int)$s->fetchColumn();
    }
}
