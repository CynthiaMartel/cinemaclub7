<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';

class Post extends ObjetoDB {
    protected int $id = 0;
    protected int $idUser = 0;  
    protected string $title = "";
    protected string $subtitle = "";
    protected string $content = "";
    protected int $visible = 0;
    protected string $editorName = "";
    protected string $img = '';


    public function __construct(int $id = 0, string $anotherKey = "", $anotherKeyValue = "") {
        parent::__construct($id, $anotherKey, $anotherKeyValue);
    }

    public function getIdPost(): int {
        return $this->id;
    }

    public function setIdPost(int $id): void {
        $this->id = $id;
    }

    public function getIdUser(): int {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): void {
        $this->idUser = $idUser;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = sanitizarString($title);
    }

    public function getSubtitle(): string {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): void {
        $this->subtitle = sanitizarString($subtitle);
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content; // CKEditor ya da contenido "limpio", por lo que no hace falta que lo saniticemos
    }
    

    public function getVisible(): int {
        return $this->visible;
    }

    public function setVisible(int $visible) : void {
        $this->visible = $visible;
    }

    public function getEditorName() : string {
        return $this->editorName;
    }

    public function setEditorName(string $editorName) {
        $this->editorName = sanitizarString($editorName);
    }

    public function save(): bool {
        return parent::save();
    }

    public function delete(): bool {
        $gestorDB = new HandlerDB();
        $where = "id = :id";
        $params = [':id' => $this->id];
        return $gestorDB->deleteRecord(TABLE_POST, $where, $params);
    }
    

    public function getImg(): string {
        return $this->img;
    }
    public function setImg(string $url): void {
        
        $this->img = trim($url);
    }
    

    public static function postList(array $data = ['id', 'title', 'subtitle', 'content', 'visible']): array {
        global $actualUser; // Asegurarse de que se tiene acceso al usuario actual
    
        $gestorDB = new HandlerDB();
    
        $where = "";
        $params = [];
    
        if (!$actualUser->isAdminOrEditor()) {
            $where = "visible = 1";
        }
    
        $records = $gestorDB->getRecords(
            TABLE_POST,
            $data,
            $where,
            $params,
            null,
            'FETCH_ASSOC'
        );
    
        return ($records !== false) ? $records : [];
    }

    // Función estática para obtener los id del Post y usarlo a la hora de visualizar al hacer click al post para leer su contenido
    public static function getPostById(int $id): ?array {
        global $actualUser;
    
        $gestorDB = new HandlerDB();
        $where = "id = :id";
        $params = [':id' => $id];
    
        $result = $gestorDB->getRecords(
            TABLE_POST,
            ['id', 'idUser', 'title', 'subtitle', 'content', 'visible', 'editorName', 'img'],
            $where,
            $params,
            null,
            'FETCH_ASSOC'
        );
    
        return ($result && count($result) > 0) ? $result[0] : null;
    }
    
    
}
?>



