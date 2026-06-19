# 🏫 SchoolFlow — Système de Gestion Scolaire

[![Laravel](https://img.shields.io/badge/Laravel-13-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue)](https://php.net)

## 📌 Sujet

SchoolFlow est une application web de gestion scolaire développée avec Laravel. Elle permet de gérer les élèves, les classes, les notes, les bulletins et les paiements de scolarité d'un établissement primaire.

## 👥 Membres du groupe

| Nom | Rôle |
|-----|------|
| Hamed Kounikorgo | Développeur principal |
| Sore Abdoulaye | Développeur principal |

## ⚙️ Installation

### Prérequis
- PHP >= 8.2
- Composer
- MySQL

### Étapes

1. Cloner le dépôt

        git clone https://github.com/hamedkounikorgo60-boop/schoolflow.git
        cd schoolflow

2. Installer les dépendances

        composer install

3. Configurer l'environnement

        cp .env.example .env
        php artisan key:generate

4. Configurer la base de données dans .env

        DB_CONNECTION=mysql
        DB_DATABASE=scolaire_db
        DB_USERNAME=laravel_user
        DB_PASSWORD=<your-secure-password>

5. Lancer les migrations

        php artisan migrate

6. Créer un compte admin

        php artisan tinker
        App\Models\User::create(['name'=>'Admin','email'=>'admin@test.com','password'=>bcrypt('<your-secure-password>'),'role'=>'gestionnaire']);

7. Démarrer le serveur

        php artisan serve

Accédez à : http://127.0.0.1:8000

## ✨ Fonctionnalités

### 👨‍💼 Gestionnaire
- Tableau de bord avec statistiques
- Gestion des élèves (CRUD)
- Gestion des classes
- Gestion des paiements avec reçus PDF
- Liste des impayés par trimestre
- Saisie et consultation des notes
- Calcul automatique des moyennes
- Classement des élèves
- Génération de bulletins PDF

### 👨‍🏫 Enseignant
- Tableau de bord personnel
- Saisie des notes
- Consultation des moyennes et classements
- Gestion des matières

## 🛠️ Technologies

| Technologie | Usage |
|-------------|-------|
| Laravel 13 | Framework PHP |
| MySQL | Base de données |
| Bootstrap 5 | Interface utilisateur |
| DomPDF | Génération de PDF |

## 📄 Licence

MIT
