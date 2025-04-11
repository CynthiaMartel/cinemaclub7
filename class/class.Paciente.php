<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';

class Roles extends ObjetoDB {
    protected int $id = 0;
    protected string | null $nif = "";
    protected string $nombre = "";
    protected string $apellidos = "";
    protected string | null $email = "";
    protected int $telefonoMovil = 0;
    protected int | null $otroTelefono = null;
    protected string $historiaClinica = "";
    protected string $observaciones = "";
    protected string $alergias = "";


    public function __construct(int $id = 0, string $otraClave = "", $valorOtraClave = "") {
        parent::__construct($id, $otraClave, $valorOtraClave);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNif(): string | null {
        return $this->nif;
    }

    public function setNif(string | null $nif): bool {
        if (is_null($nif)) {
            $this->nif = null;
            return true;
        }
        $this->nif = $nif;
        return true;
    }

    public function getNombre(): string {
        return sanitizarString($this->nombre);
    }

    public function setName($nombre): void {
        $this->nombre = sanitizarString($nombre);
    }

    public function getApellidos(): string {
        return sanitizarString($this->apellidos);
    }

    public function setApellidos(string $apellidos): void {
        $this->apellidos = sanitizarString($apellidos);
    }

    public function getNombreCompleto(): string {
        return sanitizarString($this->nombre." ".$this->apellidos);
    }

    public function getEmail(): string | null {
        return sanitizarString($this->email);
    }

    public function setEmail(string | null $email): bool {
        if (is_null($email)) {
            $this->email = null;
            return true;
        }

        if (validarEmail($email)) {
            $this->email = $email;
            return true;
        }
        return false;
    }

    public function getTelefonoMovil(): int {
        return $this->telefonoMovil;
    }

    public function setTelefonoMovil(int $telefonoMovil): void {
        $this->telefonoMovil = $telefonoMovil;
    }

    public function getOtroTelefono(): int | null {
        return $this->otroTelefono;
    }

    public function setOtroTelefono(int | null $otroTelefono): void {
        $this->otroTelefono = $otroTelefono;
    }

    public function getHistoriaClinica(): string {
        return $this->historiaClinica;
    }

    public function setHistoriaClinica(string $historiaClinica): void {
        $this->historiaClinica = sanitizarString($historiaClinica);
    }

    public function getObservaciones(): string {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): void {
        $this->observaciones = sanitizarString($observaciones);
    }

    public function getAlergias(): string {
        return $this->alergias;
    }

    public function setAlergias(string $alergias): void {
        $this->alergias = sanitizarString($alergias);
    }

    public function sodium_crypto_pwhash_str_verify(): bool {
        if ($this->nombre == "" || $this->apellidos == "" || $this->telefonoMovil == "") {
            return false;
        }

        return parent::save();
    }
}
?>