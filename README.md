![Portada](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/portada_readme.jpg)

# CinemaClub7

## 📖 Descripción  
**CinemaClub7** es una plataforma web para **buscar**, **puntuar** y **gestionar** películas. Permite a usuarios registrados explorar un catálogo de films de los últimos dos años, valorar cada título y leer posts relacionados con el mundo del cine.
En el caso de tener el rol de Admin o Editor, también se podrá crear, editar, borrar, y marcar como visible los posts que los usuarios "regulares", podrán leer. El Editor o Admin, tendrá acceso a todos los posts, sean o no visibles.
---

**A tener en cuenta**  
  - La base de datos se aloja en la carpeta llamada "Base de datos" con el archivo correspondiente en formato .sql.
  - La contraseña para todos los usuarios que ahí aparece, siempre es: Probando.1. Si quieres entrar como administrador para ver los cambios entre usuarios regulares, puedes hacer el loguin con : 
    cynthiamartel@gmail.com
  - Hay una carpeta en img/img_README, para la subida de las imágenes de muestra del fronted incluidas en este archivo README

![Modal Login](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_login.jpg)

![Vista Logueo](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_logueo.jpg)


## 👥 Roles de Usuario  
La aplicación distingue tres roles, almacenados en el campo `idRol` de la tabla **users**:

| Rol    | idRol | Permisos principales                                                                 |
| ------ | :---: | ------------------------------------------------------------------------------------- |
| User   |   3   | • Ver películas y posts visibles<br>• Puntuar (si está logueado)                     |
| Editor |   2   | • Todo lo de “User”<br>• Crear, editar y borrar posts<br>• Marcar posts como visibles |
| Admin  |   1   | • Todo lo de “Editor”<br>• Gestión completa de usuarios y contenido                  |


---

## ⚙️ Funcionalidades principales  

1. **Búsqueda de películas**  
   - Barra de búsqueda dinámica (p. ej. `"The"`)  
   - Página `searchFilms.php` con listado de resultados  
   - Clic en la película → ficha detallada  
   - Sistema de estrellas Bootstrap para votar (solo usuarios autenticados)

### 🔎 Barra de búsqueda
![Barra búsqueda películas](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/barra_b%C3%BAsqueda_pel%C3%ADculas.jpg)

### 🔍 Vista de búsqueda de películas
![Vista búsqueda películas](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_b%C3%BAsqueda_pel%C3%ADculas.jpg)


2. **Sistema de puntuación**  
   - Valoración individual de 0.0 a 5.0 
   - Feedback visual con estrellas que cambian a rojo corporativo (`#C62C0A`)  
   - Cálculo de puntuación global como media de todas las valoraciones

### ❌ Vista de votos (usuario no logueado)
![Votos no logueado](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_votos_no_logueado.jpg)

### ✅ Vista de votos (usuario logueado)
![Votos logueado](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_votos_logueado.jpg)


3. **Gestión de posts**  
   - Modal de creación/edición con CKEditor (título, subtítulo, contenido, imagen)  
   - Control de visibilidad (`visible = 1`/`0`)  
   - Solo “Editor” y “Admin” pueden crear, editar o borrar  
   - **Descarga de contenido**: cada post incluye un botón “Descargar contenido” que genera un archivo (HTML o texto) con el body completo del post
  
### 🔒 Vista de posts para usuarios sin loguear, sin ser Admin o Editor
![Vista posts sin permisos](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vistas_post_sinloguear_o_sin_SerAdmin_o_Editor.jpg)

### 🛠️ Vista de posts para Admin o Editor
![Vista posts Admin/Editor](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_posts_Admin_Editor.jpg)

### ➕ Modal para añadir un nuevo post
![Modal añadir post](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_a%C3%B1adir_post.jpg)

### ♻️ Modal para actualizar un post existente
![Modal actualizar post](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_actualizar_post.jpg)
  

4. **Autenticación y gestión de usuarios**
   - Al iniciar sesión:  
     - Reemplazo de “Login” por nombre de usuario + dropdown (Logout, Cambiar contraseña)  
     - Avatar por defecto
   - Registro vía modal “Únete a la comunidad” (nombre, email, contraseña + confirmación)  
   - Prevención de duplicados en el email    
   - **Email de bienvenida**: al registrarse, se dispara `sendWelcomeEmail.php` (ubicado en la carpeta `libreria/`), que lee credenciales y configuraciones del archivo `.env` para enviar un correo de bienvenida personalizado  
   - Modal de cambio de contraseña con validaciones mínimas

## 🔐 Autenticación de usuarios

### 🔑 Modal de login
![Modal login](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_login.jpg)

### 📂 Vista del desplegable (dropdown) del perfil del usuario logueado
![Desplegable Mi Perfil](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/desplegable_miperfil.jpg)

### 🆕 Modal para crear nueva cuenta
![Modal crear cuenta](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_crear_nueva_cuenta.jpg)

### 🚫 Prevención de duplicados al crear cuenta
![Evita duplicados](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/modal_crear_nueva_cuenta_evita_inserci%C3%B3n_duplicados.jpg)

### 🔁 Vista para cambio de contraseña
![Cambio de contraseña](https://raw.githubusercontent.com/CynthiaMartel/cinemaclub7/main/img/img_README/vista_cambio_contrase%C3%B1a.jpg)


6. **Población de la base de datos**  
   - **TMDB API (API OFICIAL)**: `getFilmsDataBase.php` rellena `films` con títulos de los últimos dos años 
   - **Wikidata**: `getFilmsDataBase.php` via SPARQL como alternativa a datos que no podemos encontrar en la API anterior como son: festivales, nominaciones y premios.
   - Registro de peticiones en `log_wikidata_requests`  

---

## 🗄 Estructura de la Base de Datos  

### Tabla: **users**
| Campo               | Tipo          | PK  | AI  | UNI | Null | Descripción                           |
| ------------------- | ------------- | :-: | :-: | :-: | :--: | ------------------------------------- |
| id                  | int(11)       | ✓   | ✓   |     |  NO  | Identificador único                   |
| name                | varchar(150)  |     |     |     |  NO  | Nombre de usuario                     |
| password            | varchar(255)  |     |     |     |  NO  | Hash de la contraseña                 |
| email               | varchar(50)   |     |     | ✓   |  NO  | Correo electrónico                    |
| idRol               | int(11)       |     |     |     |  NO  | FK → tabla `roles`                    |
| ipLastAccess        | varchar(20)   |     |     |     |  NO  | Última IP de acceso                   |
| dateHourLastAccess  | datetime      |     |     |     |  NO  | Fecha y hora del último acceso        |
| failedAttempts      | smallint(6)   |     |     |     |  NO  | Intentos fallidos de login            |
| blocked             | tinyint(1)    |     |     |     |  NO  | Usuario bloqueado (1 = sí, 0 = no)    |

### Tabla: **roles**  
_Define los roles disponibles_  
| Campo   | Tipo         | PK | AI | Descripción        |
| ------- | ------------ | :-:| :-:| ------------------ |
| idRol   | int(11)      | ✓  |    | Identificador rol  |
| name    | varchar(50)  |    |    | Nombre del rol     |

### Tabla: **films**
| Campo             | Tipo            | Null | Descripción                                     |
| ----------------- | --------------- | :--: | ----------------------------------------------- |
| idFilm            | int(11)         |  NO  | PK, auto_increment                              |
| title             | varchar(255)    |  NO  | Título                                          |
| directedBy        | varchar(255)    |  NO  | Director(es)                                    |
| genre             | varchar(100)    |  NO  | Género                                          |
| origin_country    | varchar(100)    |  NO  | País de origen                                  |
| original_language | varchar(100)    |  NO  | Idioma original                                 |
| overview          | text            |  NO  | Sinopsis                                        |
| duration          | int(11)         |  NO  | Duración (min)                                  |
| castCrew          | text            |  NO  | Reparto y equipo                                |
| release_date      | date            |  NO  | Fecha de estreno                                |
| frame             | varchar(225)    |  NO  | URL de imagen de portada                        |
| awards            | text            |  YES | Premios                                         |
| nominations       | text            |  YES | Nominaciones                                    |
| festivals         | text            |  YES | Festivales                                      |
| vote_average      | float           |  NO  | Puntuación media (TMDB)                         |
| individualRate    | float           |  NO  | Valoración del usuario logueado (sesión actual)|
| globalRate        | float           |  NO  | Puntuación global calculada en la app           |

### Tabla: **individual_rate**
| Campo   | Tipo         | PK  | Descripción                      |
| ------- | ------------ | :-: | -------------------------------- |
| id      | int(11)      | ✓   | PK, auto_increment               |
| rate    | decimal(2,1) |     | Valoración de 0.0 a 10.0         |
| idUser  | int(11)      |     | FK → `users.id`                  |
| idFilm  | int(11)      |     | FK → `films.idFilm`              |

### Tabla: **post**
| Campo       | Tipo           | PK  | Descripción                                      |
| ----------- | -------------- | :-: | ------------------------------------------------ |
| id          | int(11)        | ✓   | PK, auto_increment                               |
| idUser      | int(11)        |     | FK → `users.id`                                  |
| title       | text           |     | Título del post                                  |
| subtitle    | text           |     | Subtítulo                                        |
| content     | text           |     | Cuerpo (almacenado desde CKEditor)               |
| img         | varchar(255)   | YES | URL de la imagen del post                        |
| visible     | tinyint(1)     |     | 1 = visible, 0 = oculto                          |
| editorName  | text           |     | Nombre del usuario que editó o creó el post      |

---

## 🚀 Posibles mejoras a futuro  

- **App multiplataforma**  
  - Desarrollo de una aplicación móvil (iOS/Android) con Flutter o React Native para acceso offline, notificaciones y cámara integrada para escanear códigos de barras de DVDs/BDs.  
- **Ampliación de catálogo**  
  - Incluir películas de más allá de los dos últimos años, añadiendo filtros por década, país o géneros menos comunes.  
- **Autenticación social**  
  - Login vía Google, o similar para agilizar el registro y potenciar el engagement.  
- **Sistema de recomendaciones**  
  - Implementar recomendaciones basadas en historiales de voto y preferencias (collaborative filtering o content-based).  
- **Sección de listas y favoritos**  
  - Permitir a usuarios crear listas personalizadas (“Veo luego”, “Top 10 de terror”, etc.) y marcar favoritos.  
- **Internacionalización (i18n)**  
  - Traducción de la interfaz a varios idiomas y localización de fechas/formato de puntuaciones.  
- **Analytics e informes**  
  - Dashboard para Admin con estadísticas de visitas, usos de búsqueda, repartos de puntuaciones y posts más leídos.  
    

