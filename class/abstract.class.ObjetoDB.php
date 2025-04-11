<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';

abstract class ObjetoDB {
    protected int $id;

    public function __construct(int $id = 0, string $anotherKey = "", $anotherKeyValue = "") {
        if ($id != 0) {
            $gestorDB = new HandlerDB();
            $records = $gestorDB->getRecords(
                TABLAS_OBJETO_DB[static::class],
                ['*'],
                'id = :id',
                [':id' => $id],
                null,
                'FETCH_ASSOC'
            );
            
            foreach ($records as $record) {
                foreach ($record as $field => $values) {
                    $this->$field = $values;
                }
            }
            return true;
            
        } else {
            if ($anotherKey != "") {
                $anotherKey = sanitizarString($anotherKey);
                $anotherKeyValue = sanitizarString($anotherKeyValue);
                $gestorDB = new HandlerDB();
                $records = $gestorDB->getRecords(
                    TABLAS_OBJETO_DB[static::class],
                    ['*'],
                    $anotherKey.' = :'.$anotherKey,
                    [':'.$anotherKey => $anotherKeyValue],
                    null,
                    'FETCH_ASSOC'
                );
                foreach ($records as $record) {
                    foreach ($record as $field => $values) {
                        $this->$field = $values;
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function save(): bool {
        $gestorDB = new HandlerDB();

        if ($this->id != 0) {
            // Hay que hacer un UPDATE
            $clavesPrimarias = array('id' => $this->id);
            return $gestorDB->actualizarRegistro(
                TABLAS_OBJETO_DB[static::class],
                get_object_vars($this),
                $clavesPrimarias
            );
        } else {
            // Hay que hacer un INSERT
            $resultado = $gestorDB->insertarRegistro(
                TABLAS_OBJETO_DB[static::class],
                get_object_vars($this),
                ['id']
            );
            if (!$resultado) {
                return false;
            } else {
                $this->id = $resultado;
                return true;
            }
        }
    }

    public function delete(): bool {
        $gestorDB = new HandlerDB();
        $clavesPrimarias = array('id' => $this->id);
        return $gestorDB->delateRecord(TABLAS_OBJETO_DB[static::class],$clavesPrimarias);
    }
}
?>
