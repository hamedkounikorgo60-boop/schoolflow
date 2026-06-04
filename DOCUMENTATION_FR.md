# 📚 Documentation SchoolFlow - Guide Complet en Français

## 🏫 Vue d'ensemble

**SchoolFlow** est une application web de gestion scolaire construite avec **Laravel 13** et **PHP 8.4**. Elle permet aux écoles de gérer les élèves, les classes, les notes, les paiements et les bulletins.

---

## 🏗️ Structure du Projet

```
suivi-scolaire/
├── app/
│   ├── Models/                 # Modèles de données
│   │   ├── Eleve.php           # Modèle Élève
│   │   ├── Classe.php          # Modèle Classe
│   │   ├── Note.php            # Modèle Note
│   │   ├── Matiere.php         # Modèle Matière
│   │   ├── Paiement.php        # Modèle Paiement
│   │   └── User.php            # Modèle Utilisateur
│   │
│   └── Http/
│       ├── Controllers/
│       │   ├── Gestionnaire/   # Contrôleurs pour le gestionnaire
│       │   │   ├── NoteController.php
│       │   │   ├── EnseignantController.php
│       │   │   └── MatiereController.php
│       │   │
│       │   ├── Enseignant/     # Contrôleurs pour l'enseignant
│       │   └── EleveController.php
│       │
│       └── Middleware/         # Middleware d'authentification
│
├── routes/
│   └── web.php                 # Définition de toutes les routes
│
├── resources/
│   └── views/                  # Templates Blade
│       ├── notes/
│       │   ├── bulletin.blade.php    # Template du bulletin PDF
│       │   ├── index.blade.php       # Liste des notes
│       │   └── classement.blade.php  # Classement des élèves
│       ├── paiements/               # Templates des paiements
│       └── gestionnaire/            # Templates du gestionnaire
│
└── database/
    ├── migrations/             # Fichiers de migration (schéma BD)
    └── seeders/               # Fichiers de remplissage initial

```

---

## 📋 Modèles de Données Principaux

### 1️⃣ Élève (Eleve)
Représente un élève de l'école.

**Attributs:**
- `matricule` - Identifiant unique
- `nom` - Nom de famille
- `prenoms` - Prénoms
- `date_naissance` - Date de naissance
- `classe_id` - Classe de l'élève
- `statut` - Actif ou inactif
- `photo` - Photo de profil

**Relations:**
- Appartient à une `Classe`
- Possède plusieurs `Note`
- Possède plusieurs `Paiement`

---

### 2️⃣ Classe
Représente une classe de l'école (CP1, CE1, CM2, etc.).

**Attributs:**
- `nom` - Nom de la classe
- `niveau` - Niveau (CP1, CE1, CM1, CM2)
- `frais_scolarite` - Montant des frais

**Relations:**
- Contient plusieurs `Élève`
- Contient plusieurs `Matière`
- A plusieurs `Enseignant`

---

### 3️⃣ Note
Représente une note obtenue par un élève dans une matière.

**Attributs:**
- `eleve_id` - ID de l'élève
- `matiere_id` - ID de la matière
- `note` - Valeur (0-20)
- `trimestre` - Trimestre (trimestre1, trimestre2, trimestre3)

**Relations:**
- Appartient à un `Élève`
- Appartient à une `Matière`

---

### 4️⃣ Matière (Matiere)
Représente une matière/sujet enseigné.

**Attributs:**
- `nom` - Nom de la matière (Mathématiques, Français, etc.)
- `coefficient` - Coefficient pour le calcul de la moyenne
- `niveau` - Niveau d'application

**Relations:**
- Contient plusieurs `Note`
- Enseignée par plusieurs `Enseignant`

---

## 🎯 Fonctionnalités Principales

### 1. Gestion des Élèves
**URL:** `/gestionnaire/eleves`

- Voir la liste de tous les élèves
- Créer un nouvel élève
- Modifier les informations d'un élève
- Supprimer un élève
- Voir les détails d'un élève

---

### 2. Gestion des Notes
**URL:** `/gestionnaire/notes`

- Saisir les notes des élèves par matière et trimestre
- Voir la liste des notes par classe
- Calculer les moyennes automatiquement

---

### 3. Classement des Élèves
**URL:** `/gestionnaire/classement`

- Voir le classement des élèves par moyenne
- Filtrer par classe et trimestre
- Voir les rangs des élèves

---

### 4. Génération des Bulletins
**URL:** `/gestionnaire/bulletin`

- Générer un bulletin PDF pour un élève
- Affiche:
  - Informations de l'élève
  - Notes obtenues
  - Moyenne générale
  - Rang dans la classe
  - Mention (Excellent, Très bien, Bien, Assez bien, Passable)

**Calcul de la Mention:**
```
Moyenne ≥ 16 → Excellent
Moyenne ≥ 14 → Très bien
Moyenne ≥ 12 → Bien
Moyenne ≥ 10 → Assez bien
Moyenne < 10 → Passable
```

---

### 5. Gestion des Paiements
**URL:** `/gestionnaire/paiements`

- Enregistrer les paiements des frais de scolarité
- Générer des reçus de paiement
- Voir les élèves ayant impayé

---

## 🔐 Authentification et Rôles

L'application a **3 rôles** différents:

### 1. Gestionnaire (Administrateur)
- Accès complet à toutes les fonctionnalités
- Gestion des élèves, classes, notes, paiements
- Génération des bulletins
- Gestion des enseignants

**URL:** `/gestionnaire`

### 2. Enseignant
- Peut saisir les notes de ses matières
- Voir les classes qu'il enseigne
- Voir le classement de ses élèves

**URL:** `/enseignant`

### 3. Élève
- Peut voir ses notes et son bulletin
- Voir son classement

**URL:** `/eleve` (non implémenté actuellement)

---

## 📊 Calcul de la Moyenne Générale

La moyenne générale est calculée **pondérée par les coefficients:**

```
Moyenne = (Σ Note × Coefficient) / Σ Coefficient
```

**Exemple:**
```
Mathématiques (coef 3):        16 × 3 = 48
Français (coef 3):            14 × 3 = 42
Sciences (coef 2):            12 × 2 = 24
Histoire-Géo (coef 1):        10 × 1 = 10

Moyenne = (48 + 42 + 24 + 10) / (3 + 3 + 2 + 1)
Moyenne = 124 / 9 = 13.78
```

---

## 🗄️ Base de Données - Tables Principales

### Table `eleves`
```sql
CREATE TABLE eleves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    matricule VARCHAR(50) UNIQUE,
    nom VARCHAR(100),
    prenoms VARCHAR(100),
    date_naissance DATE,
    classe_id INT,
    photo VARCHAR(255),
    statut VARCHAR(20),
    ...
)
```

### Table `notes`
```sql
CREATE TABLE notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    eleve_id INT,
    matiere_id INT,
    note DECIMAL(4,2),
    trimestre VARCHAR(20),
    ...
)
```

### Table `matieres`
```sql
CREATE TABLE matieres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    coefficient INT,
    niveau VARCHAR(20),
    ...
)
```

### Table `classes`
```sql
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    niveau VARCHAR(20),
    frais_scolarite DECIMAL(10,2),
    ...
)
```

---

## 🔄 Flux de Travail Typique

### 1. Ajout d'un Élève
```
Gestionnaire → /gestionnaire/eleves/create → Saisir les infos → POST → BD
```

### 2. Saisie d'une Note
```
Gestionnaire → /gestionnaire/notes → Sélectionner élève & matière → Saisir note → POST → BD
```

### 3. Génération d'un Bulletin
```
Gestionnaire → /gestionnaire/bulletin?eleve_id=1&trimestre=1
   → Récupère les notes
   → Calcule moyenne
   → Détermine mention
   → Génère PDF
   → Télécharge
```

---

## 💾 Migrations Importantes

Les migrations créent la structure de la base de données:

- `create_eleves_table.php` - Crée la table des élèves
- `create_notes_table.php` - Crée la table des notes
- `create_matieres_table.php` - Crée la table des matières
- `create_classes_table.php` - Crée la table des classes
- `add_photo_to_eleves_table.php` - Ajoute le champ photo
- `remove_duplicate_matieres.php` - Nettoie les doublons

---

## 🎨 Templates Blade Principaux

### bulletin.blade.php
Génère le PDF du bulletin de notes avec:
- En-têtes et métadonnées
- Informations de l'élève
- Tableau des notes
- Moyenne générale et rang
- Mentions en couleur
- Signatures du directeur, enseignant, parent

---

## 🚀 Commandes Utiles

### Démarrer le serveur de développement
```bash
php artisan serve
```

### Générer le cache des routes
```bash
php artisan route:cache
```

### Nettoyer le cache des routes
```bash
php artisan route:clear
```

### Exécuter les migrations
```bash
php artisan migrate
```

### Consulter la base de données (Tinker)
```bash
php artisan tinker
```

---

## 📞 Support et Déboggage

### Erreurs Courantes

**1. "Variable non définie" dans un template:**
- Vérifiez que toutes les variables sont passées depuis le contrôleur
- Utilisez `dd()` pour déboguer

**2. Erreur de migration:**
- Vérifiez la syntaxe SQL
- Vérifiez que les migrations s'exécutent dans le bon ordre

**3. Problème de route:**
- Exécutez `php artisan route:clear`
- Vérifiez le nom de la route avec `php artisan route:list`

---

## 📚 Ressources Supplémentaires

- **Laravel Documentation:** https://laravel.com/docs
- **PHP Documentation:** https://www.php.net/manual
- **Blade Templates:** https://laravel.com/docs/blade
- **Eloquent ORM:** https://laravel.com/docs/eloquent

---

**Version:** 1.0  
**Dernière mise à jour:** 4 juin 2026  
**Développeur:** SchoolFlow Team
