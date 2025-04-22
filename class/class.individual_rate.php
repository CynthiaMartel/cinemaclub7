<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';

class IndividualRate extends ObjetoDB {
    protected int $idIndividualRate;
    protected float $rate;
    protected int $idUser;
    protected int $idFilm;

    public function __construct(int $idIndividualRate = 0, float $rate = 0.0, int $idUser = 0, int $idFilm = 0) {
        parent::__construct($idIndividualRate);
        $this->rate = $rate;
        $this->idUser = $idUser;
        $this->idFilm = $idFilm;
    }

    public function getRate(): float {
        return $this->rate;
    }

    public function setRate(float $rate): void {
        $this->rate = $rate;
    }

    public function getIdUser(): int {
        return $this->idUser;
    }

    public function getIdFilm(): int {
        return $this->idFilm;
    }

}