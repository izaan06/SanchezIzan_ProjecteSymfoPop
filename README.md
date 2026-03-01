# SymfoPop - Mercat de Segona Mà

SymfoPop és una aplicació web de mercat de segona mà on els usuaris poden registrar-se, publicar els seus propis productes, i navegar pel catàleg de productes de la comunitat.

## 🚀 Funcionalitats

- **👤 Usuaris**: Registre, inici de sessió i gestió de perfil.
- **🛒 Catàleg**: Llistat públic de productes amb detalls complets.
- **➕ Gestió de Productes**: Crear, editar i eliminar els teus propis productes (CRUD complet).
- **🔒 Seguretat**: Accés protegit a funcions d'usuari, validació de propietari i protecció CSRF.
- **📱 Disseny**: Interfície responsive utilitzant Bootstrap 5 i Twig.

## 🛠️ Tecnologies Utilitzades

- **Framework**: Symfony 6/7
- **ORM**: Doctrine (MySQL/MariaDB)
- **Motor de Plantilles**: Twig
- **Estils**: Bootstrap 5
- **Dades de prova**: DoctrineFixturesBundle + Faker

## 📦 Instal·lació

1. **Clonar el repositori**:

    ```bash
    git clone <url-del-repositori>
    cd symfopop
    ```

2. **Instal·lar dependències**:

    ```bash
    composer install
    ```

3. **Configurar l'entorn**:
   Copia el fitxer `.env.example` a `.env` i configura les teves credencials de base de dades:

    ```bash
    cp .env.example .env
    ```

4. **Crear la base de dades i executar migracions**:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. **Carregar dades de prova (opcional)**:

    ```bash
    php bin/console doctrine:fixtures:load
    ```

6. **Iniciar el servidor**:
    ```bash
    symfony serve
    ```

## 🎥 Guia de Lliurament

### Vídeo Demostratiu

El vídeo realitza un recorregut per:

1. **Navegació Pública**: Home i detall de productes.
2. **Registre i Auth**: Creació d'usuari i login.
3. **CRUD**: Crear, editar i esborrar productes.
4. **Explicació de Codi**: Entitats, Controladors, Formularis i Seguretat.

### 📁 Enllaços al Codi

- [📁 Entitats](src/Entity)
- [🎮 Controladors](src/Controller)
- [📝 Formularis](src/Form)
- [🎨 Vistes (Twig)](templates)
- [🔒 Seguretat](config/packages/security.yaml)

---

Projecte realitzat per al mòdul de Programació web en entorn servidor (DAW 2).
