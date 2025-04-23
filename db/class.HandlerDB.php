<?php
/******************************************************************************
 * Gestión de la Base de Datos con PDO
 * Contiene métodos para:
 * - Obtener registros (SELECT)
 * - Insertar registros (INSERT INTO)
 * - Actualizar registros (UPDATE)
 * - Borrar registros (DELETE)
 * Gestiona los errores almacenándolos en el fichero log de la BD
 ******************************************************************************/

# Variables de conexión con la Base de Datos
const DB_HOST = CONFIG_DB['DB_HOST'];
const DB_USER = CONFIG_DB['DB_USER'];
const DB_PASSWORD = CONFIG_DB['DB_PASSWORD'];
const DB_NAME = CONFIG_DB['DB_NAME'];

# Ruta fichero log
const FICHERO_LOG_DB = CONFIG_DB['DB_LOG_FILE'];

# Nombres de las tablas
const TABLE_USERS = 'users';
const TABLE_ROL = 'rol';
const TABLE_POST = 'post';
const TABLE_FILM = 'films';
const TABLE_INDIVIDUAL_RATE = 'individual_rate';

const TABLAS_OBJETO_DB = [
    'User' => TABLE_USERS,
    'Post'  => TABLE_POST,
    'Rol'  => TABLE_ROL ,
    'IndividualRate' => TABLE_INDIVIDUAL_RATE
];

# Definición de la clase GestorDBPDO
class HandlerDB {
    private string $DB_HOST = DB_HOST;
    private string $DB_USER = DB_USER;
    private string $DB_PASSWORD = DB_PASSWORD;
    private string $DB_NAME = DB_NAME;

    public PDO $dbh;    // Manejador de la conexión con la base de datos
    public string $error = "";  // Almacena el último error producido
    public string $lastQuery;  // Almacena la última consulta ejecutada

    /******************************************************************************
     * Constructor
     * Si no se le pasan parámetros, utiliza los que están definidos por defecto
     * en las constantes del principio de este código.
     * Si falla, genera excepción y la almacena en el log de la base de datos.
     ******************************************************************************/
    public function __construct($db_host = null, $db_user = null, $db_password = null, $db_name = null) {

        if ($db_host != null) {
            $this->DB_HOST = $db_host;
        }

        if ($db_user != null) {
            $this->DB_USER = $db_user;
        }

        if ($db_password != null) {
            $this->DB_PASSWORD = $db_password;
        }

        if ($db_name != null) {
            $this->DB_NAME = $db_name;
        }

        try {
            $dsn = "mysql:host={$this->DB_HOST};dbname={$this->DB_NAME}";
            $this->dbh = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
            $this->dbh->exec("set names utf8");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s').": ".$e->getMessage();
            file_put_contents(FICHERO_LOG_DB, PHP_EOL.$mensajeLog.PHP_EOL.PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }



    /***********************************************************************************************
     * obtenerRegistros
     * Obtiene de la tabla especificada los registros que cumplan los criterios:
     * - String $tabla: nombre la tabla sobre la que aplicar la consulta
     * - Array $datosRequeridos: campos necesarios Ejemplo: ["*"] o ["id","nombre"]
     * - String $clausulaWhere: Ejemplo: nombre = :nombre AND email LIKE :email
     * - Array $parametrosWhere: Ejemplo: [":nombre" => "Juan", ":email" => "%gmail.com",...]
     * - String $ordenSeleccion: Ejemplo: nombre, apellidos ASC
     * - String $tipoFetch: "FETCH_ASSOC" => Devuelve los resultados en forma de array asociativo
     *                      "_OBJ" => Devuelve los resultados en forma de objeto
     *                      Existen otro tipo de FETCH, pero deben ser ejecutados de otra forma
     * - String $limit: Ejemplo: "10" (Cogería los 10 primeros registros)
     *                  Ejemplo: "10, 5" (Cogería los registros del 11 al 15)
     *                  Para más ejemplos, leer sobre cláusula LIMIT
     * - String $offset: Funciona en combinación con $limit
     *                   Ejemplo: $limit = 10 | $offset = 5 -> Devolverá registros del 6 al 15
     * Devuelve false si la consulta no se pudo ejecutar y almacena el error en el log
     * Devuelve el conjunto de resultados si la consulta se pudo ejecutar correctamente
     ***********************************************************************************************/
    public function getRecords(string $tabla, array $datosRequeridos, string $clausulaWhere, array | null $parametrosWhere, string | null $ordenSeleccion, string $tipoFetch, int | null $limit = null, int | null $offset = null): array | false {
        // Preparamos la consulta
        $sqlParametrosSelect = implode(",",$datosRequeridos);
        $dataSqlQuery = "SELECT {$sqlParametrosSelect} FROM {$tabla}";

        if ($clausulaWhere != null) {
            $dataSqlQuery .= " WHERE {$clausulaWhere} ";
        }

        if ($ordenSeleccion != null) {
            $dataSqlQuery .= " ORDER BY {$ordenSeleccion} ";
        }

        if ($limit != null) {
            $dataSqlQuery .= " LIMIT {$limit} ";
        }

        if ($offset != null) {
            $dataSqlQuery .= " OFFSET {$offset} ";
        }

        $this->lastQuery = $dataSqlQuery;

        try {
            $dataSqlQuery = $this->dbh->prepare($dataSqlQuery);
            foreach($parametrosWhere as $parametro => $values) {
                $dataSqlQuery->bindValue($parametro, $values);
            }
            $dataSqlQuery->execute();
            return $dataSqlQuery->fetchAll(constant('PDO::'.$tipoFetch));
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s').": ".$e->getMessage();
            file_put_contents(FICHERO_LOG_DB, $mensajeLog.PHP_EOL.$this->lastQuery.PHP_EOL.PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }

/***********************************************************************************************
     * Borrar registros
     * 
     ***********************************************************************************************/
    public function deleteRecord(string $tabla, string $clausulaWhere, array $parametrosWhere): bool {
        $sql = "DELETE FROM {$tabla}";
    
        if (!empty($clausulaWhere)) {
            $sql .= " WHERE {$clausulaWhere}";
        }
    
        $this->lastQuery = $sql;
    
        try {
            $stmt = $this->dbh->prepare($sql);
            foreach ($parametrosWhere as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s') . ": " . $e->getMessage();
            file_put_contents(FICHERO_LOG_DB, $mensajeLog . PHP_EOL . $this->lastQuery . PHP_EOL . PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }
    

    /***********************************************************************************************
     * insertarRegistro
     * Inserta un registro en la base de datos. Es, por tanto, adecuado para inserciones de pocos registros.
     * En caso de que se quieran insertar varios se debe utilizar el handler ($this->dbh) y prepara la consulta
     * para hacer múltiples inserciones.
     *
     * - String $tabla: nombre la tabla sobre la que aplicar la consulta
     * - Array $datos: campos a insertar. Ejemplo: ["nombre" => "Juan", "edad" => 27,...]
     * - Array $valoresesAutonumerico: Podemos indicar los valores autonuméricos de la tabla para evitar que haya
     *                               problemas con la inserción. Ejemplo: ["id"]
     * Devuelve el id del último registro insertado en el caso de que la consulta se haya podido ejecutar
     * Devuelve false si no se pudo ejecutar la consulta y guarda el error en el log
     ***********************************************************************************************/
    public function insertarRegistro($tabla,$data,$valoresAutonumerico = null): int | false {
        
        $arrayConsultaParametros = array();
        $arrayConsultaParametrosPDO = array();
        $arrayConsultaValores = array();

        // Cargamos en un array todos los campos a insertar
        foreach($data as $field => $values) {
            if (!in_array($field, $valoresAutonumerico)) {
                array_push($arrayConsultaParametros, $field);
                array_push($arrayConsultaParametrosPDO, ':'.$field);
                $arrayConsultaValores[$field] = $values;
            }
        }

        // Preparamos la consulta
        $sqlParametros = implode(",",$arrayConsultaParametros);
        $sqlParametrosPDO = implode(",",$arrayConsultaParametrosPDO);
        $dataSqlQuery = "INSERT INTO {$tabla} ({$sqlParametros}) VALUES ({$sqlParametrosPDO})";

        $this->lastQuery = $dataSqlQuery;

        try {
            $this->dbh->prepare($dataSqlQuery)->execute($arrayConsultaValores);
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s') . ": " . $e->getMessage();
            file_put_contents(FICHERO_LOG_DB, $mensajeLog.PHP_EOL.$this->lastQuery.PHP_EOL.PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }


    /***********************************************************************************************
     * actualizarRegistro
     * Actualiza un registro de la base de datos a partir de una clave primaria (que puede ser compuesta)
     *
     * - String $tabla: nombre la tabla sobre la que aplicar la consulta
     * - Array $datos: campos a actualizar. Ejemplo: ["nombre" => "Juan", "edad" => 27,...]
     * - Array $clavesPrimarias: Ejemplo: ["dni" => 12345678W, "email" => "pepe@gmail.com",...]
     * Devuelve true en el caso de que la consulta se haya podido ejecutar
     * Devuelve false si no se pudo ejecutar la consulta y guarda el error en el log
     ***********************************************************************************************/
    public function actualizarRegistro($tabla,$data,$clavesPrimarias): bool {
        $arrayConsultaParametrosPDO = array();
        $arrayConsultaValores = array();

        // Cargamos en un array todos los field$fields a insertar
        foreach($data as $field => $values) {
            if (!in_array($field, $clavesPrimarias)) {
                array_push($arrayConsultaParametrosPDO, $field.'=:'.$field);
                $arrayConsultaValores[$field] = $values;
            }
        }

        // Cargamos los parámetros de la cláusula WHERE
        $arrayParametrosWhere = array();
        foreach($clavesPrimarias as $field => $values) {
            if (is_string($values)) {
                $arrayParametrosWhere[] = $field.'="'.$values.'"';
            } else {
                $arrayParametrosWhere[] = $field.'='.$values;
            }
        }

        // Preparamos la consulta
        $sqlParametrosPDO = implode(",",$arrayConsultaParametrosPDO);
        $sqlParametrosWhere = implode(" AND ",$arrayParametrosWhere);
        $dataSqlQuery = "UPDATE {$tabla} SET {$sqlParametrosPDO} WHERE {$sqlParametrosWhere}";

        $this->lastQuery = $dataSqlQuery;

        try {
            $this->dbh->prepare($dataSqlQuery)->execute($arrayConsultaValores);
            return true;
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s') . ": " . $e->getMessage();
            file_put_contents(FICHERO_LOG_DB, $mensajeLog.PHP_EOL.$this->lastQuery.PHP_EOL.PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }

    /***********************************************************************************************
     * eliminarRegistro
     * Elimina un registro de una tabla especificando la clave primaria (puede ser compuesta)
     *
     * - String $tabla: nombre la tabla sobre la que aplicar la consulta
     * - Array $clavesPrimarias: Ejemplo: ["dni" => 12345678W, "email" => "pepe@gmail.com",...]
     * Devuelve true en el caso de que la consulta se haya podido ejecutar
     * Devuelve false si no se pudo ejecutar la consulta y guarda el error en el log
     ***********************************************************************************************/
    public function eliminarRegistro($tabla,$clavesPrimarias): bool {
        $arrayValoresPDO = array();
        $arrayParametrosWhere = array();
        foreach($clavesPrimarias as $clavePrimaria => $values) {
            $arrayValoresPDO[':'.$clavePrimaria] = $values;
            $arrayParametrosWhere[] = $clavePrimaria.' = :'.$clavePrimaria;
        }

        // Preparamos la consulta
        $sqlParametrosWhere = implode(" AND ",$arrayParametrosWhere);
        $dataSqlQuery = "DELETE FROM {$tabla} WHERE {$sqlParametrosWhere}";

        $this->lastQuery = $dataSqlQuery;

        try {
            $this->dbh->prepare($dataSqlQuery)->execute($arrayValoresPDO);
            return true;
        } catch (PDOException $e) {
            $mensajeLog = date('Y-m-d H:i:s') . ": " . $e->getMessage();
            file_put_contents(FICHERO_LOG_DB, $mensajeLog.PHP_EOL.$this->lastQuery.PHP_EOL.PHP_EOL, FILE_APPEND);
            $this->error = $e->getMessage();
            return false;
        }
    }
}
?>