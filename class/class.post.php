<?php
require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/function.globales.php';
require_once __DIR__ . '/abstract.class.ObjetoDB.php';

class Post extends ObjetoDB {
    protected int $id = 0;
    protected int $idUser = 0;  
    protected string $title = "";
    protected string $content = "";
    protected int $visible = 0;
    protected string $editorName = "";

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

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = sanitizarString($content);
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

    public static function postList(array $data = ['id', 'title', 'content']): array {
        $gestorDB = new HandlerDB();
        $records = $gestorDB->getRecords(
            TABLE_POST,
            $data,
            '',      // <= cláusula WHERE vacía
            [],      // <= parámetros WHERE vacíos
            null, 
            'FETCH_ASSOC'
        );

        return ($records !== false) ? $records : [];
    }
}
?>



