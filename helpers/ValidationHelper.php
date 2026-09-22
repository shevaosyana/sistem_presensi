<?php
/** Validation Helper */
class ValidationHelper {
    private array $errors = [];
    private array $data;
    public function __construct(array $data) { $this->data = $data; }

    public function required(string $f, string $lbl): static {
        if (trim($this->data[$f]??'') === '') $this->errors[$f] = "$lbl wajib diisi.";
        return $this;
    }
    public function minLength(string $f, int $min, string $lbl): static {
        $v=trim($this->data[$f]??'');
        if ($v!=='' && mb_strlen($v)<$min) $this->errors[$f]="$lbl minimal $min karakter.";
        return $this;
    }
    public function maxLength(string $f, int $max, string $lbl): static {
        if (mb_strlen($this->data[$f]??'')>$max) $this->errors[$f]="$lbl maksimal $max karakter.";
        return $this;
    }
    public function numeric(string $f, string $lbl): static {
        $v=$this->data[$f]??'';
        if ($v!=='' && !is_numeric($v)) $this->errors[$f]="$lbl harus angka.";
        return $this;
    }
    public function inList(string $f, array $list, string $lbl): static {
        $v=$this->data[$f]??'';
        if ($v!=='' && !in_array($v,$list)) $this->errors[$f]="$lbl tidak valid.";
        return $this;
    }
    public function matches(string $f, string $cf, string $lbl): static {
        if (($this->data[$f]??'')!==($this->data[$cf]??'')) $this->errors[$cf]="$lbl tidak cocok.";
        return $this;
    }
    public function year(string $f, string $lbl): static {
        $v = trim($this->data[$f] ?? '');
        if ($v !== '' && (!ctype_digit($v) || (int)$v < 1900 || (int)$v > 2100))
            $this->errors[$f] = "$lbl harus berupa tahun yang valid (1900-2100).";
        return $this;
    }
    public function email(string $f, string $lbl): static {
        $v = trim($this->data[$f] ?? '');
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL))
            $this->errors[$f] = "$lbl harus berupa alamat email yang valid.";
        return $this;
    }
    public function unique(string $f, string $table, string $column, string $lbl, ?int $exceptId = null): static {
        $v = trim($this->data[$f] ?? '');
        if ($v !== '') {
            $db = Database::getInstance();
            $sql = "SELECT COUNT(*) FROM $table WHERE $column = ?";
            $params = [$v];
            if ($exceptId) { $sql .= " AND id != ?"; $params[] = $exceptId; }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetchColumn() > 0) $this->errors[$f] = "$lbl sudah terdaftar.";
        }
        return $this;
    }
    public function time(string $f, string $lbl): static {
        $v = trim($this->data[$f] ?? '');
        if ($v !== '' && !preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/', $v)) {
            $this->errors[$f] = "$lbl harus berupa format waktu yang valid (HH:MM).";
        }
        return $this;
    }
    public function fails(): bool  { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function firstError(): string { return !empty($this->errors)?array_values($this->errors)[0]:''; }
}
