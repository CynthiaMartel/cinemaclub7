<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';

class User extends ObjetoDB {
    protected int $id = 0;
    protected string $name = "";
    protected string $password = "";
    protected string $email = "";
    protected int $idRol = 0;
    protected string $ipLastAccess = "";
    protected string | null $dateHourLastAccess = "";
    protected int $failedAttempts = 0;
    protected int $blocked = 0;

    public function __construct(int $id = 0, string $anotherKey = "", $anotherKeyValue = "") {
        parent::__construct($id, $anotherKey, $anotherKeyValue);
    }

    public function getidUser(): int {
        return $this->id;
    }

    public function getName(): string {
        return sanitizarString($this->name);
    }

    public function setName($name): void {
        $this->name = sanitizarString($name);
    }

    public function getEmail(): string {
        return sanitizarString($this->email);
    }

    public function setIdRol(int $idRol): void {
        $this->idRol = $idRol;
    }
    
    public function getIdRol(): int {
        return $this->idRol;
    }

    public function setEmail($email): bool {
        if (validarEmail($email)) {
            $this->email = $email;
            return true;
        }
        return false;
    }

    public function setPassword(string $unencryptedPassword): void {
        $this->password = password_hash($unencryptedPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function checkPassword(string $unencryptedPassword): bool {
        return password_verify($unencryptedPassword, $this->password);
    }

    public function getIpLastAccess(): string {
        return sanitizarString($this->ipLastAccess);
    }

    public function setIpLastAccess(string $ipLastAccess): void {
        $this->ipLastAccess = $ipLastAccess;
    }

    public function getDateHourLastAccess(bool $formatted = false): string {
        if ($formatted) {
            return date('d/m/Y H:i', strtotime($this->dateHourLastAccess));
        }
        return $this->dateHourLastAccess;
    }

    public function setDateHourLastAccess(string $dateHourLastAccess): void {
        $this->dateHourLastAccess = $dateHourLastAccess;
    }

    public function getFailedAttempts(): int {
        return $this->failedAttempts;
    }

    public function setFailedAttempts(int $failedAttempts): void {
        $this->failedAttempts = $failedAttempts;
    }

    public function getBlocked(): int {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): void {
        $this->blocked = $blocked;
    }

    public function save(): bool {
        return parent::save();
    }

    // Función para saber que tiene el rol de Admin o Editor. Se usará esta función para mostrar el modal de Editar-Crear Post solo a los usuarios que sean Admin o Editor
    public function isAdminOrEditor(): bool {
        return in_array($this->getIdRol(), [1, 2]);
    }
}


function userList(array $data): array {
    $gestorDB = new HandlerDB();
    $records = $gestorDB->getRecords(
        TABLE_USERS,
        $data,
        null,
        'FETCH_ASSOC'
    );

    if (isset($records[0]['id']) && $records[0]['id'] > 0) {
        return $records;
    }

    return [];
}

?>