<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';
require_once 'RolTipo.php';


enum rolType: string {
    case Admin = 'Admin';
    case User = 'User';
    case Editor = 'Editor';
}

class Rol extends ObjetoDB {
    protected int $id = 0;
    protected rolType $rolType;

    public function __construct(int $id = 0, string $anotherKey = "", $anotherKeyValue = "") {
        parent::__construct($id, $anotherKey, $anotherKeyValue);
    }

    public function getIdRol(){
        return $this->id;
    }
    public function getRol(): rolType {
        return $this->rolType;
    }
    public function setRol(enum rolType: string){
        $this->rolType = $rolType;
    }
    public function setIdRol(){
        $this-> id= $id;
    }
}

function rolList(array $data): array {
    $gestorDB = new HandlerDB();
    $records = $gestorDB->getRecords(
        TABLE_ROL,
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