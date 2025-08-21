# 🎬 Répertoire de Films — PHP/MySQL

> **Projet pédagogique** réalisé dans le cadre de la préparation au **Titre Professionnel Développeur Web & Web Mobile (TP DWWM)**.  
> Objectif : mettre en œuvre un **CRUD complet** avec **PHP (PDO)** et **MySQL**, incluant recherche, tri, pagination, et bonnes pratiques de sécurité côté serveur.

---

## 📌 Présentation

Application web simple permettant de **gérer un répertoire de films** :
- ➕ Ajout d’un film
- 📖 Consultation (liste + page détail)
- ✏️ Modification
- ❌ Suppression
- 🔎 Recherche (titre/réalisateur)
- ↕️ Tri (titre, réalisateur, année)
- 📄 Pagination (5 films/page)
- 🏷️ Gestion des **genres** (CRUD)

Les opérations BD utilisent **PDO + requêtes préparées** et les sorties sont échappées pour limiter les injections SQL et XSS.

---

## 🛠️ Stack technique

- **PHP 8+**
- **MySQL** (via **PDO**)
- **HTML5/CSS3**
- **JavaScript** pour l’affichage des messages
- Serveur local : **XAMPP**

---

## 📂 Structure du projet

```
/gestion-films/
│
├── index.php                 # Accueil + liste (recherche, tri, pagination)
├── film.php                  # Détails d’un film
├── ajouter.php               # Ajout d’un film (validation serveur)
├── modifier.php              # Modification d’un film (validation serveur)
├── supprimer.php             # Suppression d’un film
│
├── genres.php                # Liste/gestion des genres
├── ajouter-genres.php        # Ajout d’un genre
├── modifier-genres.php       # Modification d’un genre
├── supprimer-genres.php      # Suppression d’un genre
│
├── includes/
│   ├── db.php                # Connexion PDO
│   ├── header.php            # <head> + header + ouverture <main>
│   ├── footer.php            # footer + fermeture </main>
│   └── menu.php              # Navigation
│
├── css/
│   └── style.css             # Styles globaux (tableaux, formulaires, messages)
│
└── js/
    └── script.js             # Affichage des messages de confirmation/erreur
```

---

## 🗄️ Base de données

### Modèle minimal
Deux tables relationnelles : **films** et **genres**.

```sql
CREATE DATABASE IF NOT EXISTS films_db;
USE films_db;

CREATE TABLE genres (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(200) NOT NULL
);

CREATE TABLE films (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(255) NOT NULL,
  realisateur VARCHAR(255),
  annee       INT,
  genre_id    INT NULL,
  resume      TEXT,
  CONSTRAINT fk_films_genres
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL
);
```

> Le libellé du genre est obtenu par **JOIN** (`LEFT JOIN`/`INNER JOIN` selon les pages). Si `genre_id` est `NULL`, l’interface affiche **« À définir »**.

---

## 🔒 Sécurité & validation (côté serveur)

- **PDO + requêtes préparées** pour toutes les écritures/lectures BD.
- **Échappement HTML** à l’affichage (`htmlspecialchars`) pour éviter les **XSS**.
- **Validation** des entrées (extraits du code) :
  - `annee` : entier entre **1900** et **2025** (`filter_var(..., FILTER_VALIDATE_INT)` + bornes).
  - `title` : non vide (trim/required).
  - `genre` : doit exister en base (`SELECT COUNT(*) FROM genres WHERE id = :genre_id`).
- Messages d’erreurs listés sous le formulaire ; messages de confirmation temporisés (JS).

---

## 🚀 Installation (local)

1. **Cloner** ou copier le projet dans votre répertoire web (ex. `htdocs/gestion-films`).  
2. **Créer la base** et les tables avec le script SQL ci-dessus (phpMyAdmin ou CLI).  
3. **Configurer** `includes/db.php` si besoin :
   ```php
   $host = 'localhost';
   $dbname = 'films_db';
   $user = 'root';
   $pass = '';
   $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
   ]);
   ```
4. **Démarrer** Apache + MySQL.
5. **Ouvrir** l’application : `http://localhost/gestion-films/index.php`.

---

## 🧭 Guide d’utilisation

- **Accueil (`index.php`)**  
  Liste paginée (5/pg). Recherche par `titre`/`realisateur` (paramètre `search`).  
  Tri par `titre|realisateur|annee` (params `tri`, `ordre=ASC|DESC`).  
  Actions : **Détail**, **Modifier**, **Supprimer** (confirmation JS).

- **Ajout (`ajouter.php`)**  
  Formulaire + validation serveur → redirection `index.php?valid=add`.

- **Modification (`modifier.php?id=…`)**  
  Pré-remplissage + validation → `index.php?valid=update`.

- **Suppression (`supprimer.php?id=…`)**  
  Suppression → `index.php?valid=delete`.

- **Genres (`genres.php`)**  
  CRUD complet sur la table `genres`.

---


## ✅ Alignement avec le référentiel TP DWWM

- **Front-end** : intégration HTML/CSS, accessibilité basique, publication locale.
- **Dynamique client** : interactions simples JS, messages utilisateur.
- **Back-end** : accès et manipulations de données, validations, sécurité (PDO/XSS), POO optionnelle.
- **Données** : conception et jointures SQL, intégrité, contraintes référentielles.
- **Documentation/déploiement** : README, procédure d’installation locale.

---

## 📜 Licence

Projet d’apprentissage — usage pédagogique dans le cadre du **TP DWWM**.

