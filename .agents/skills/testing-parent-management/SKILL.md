---
name: testing-schoolflow-parent-management
description: Test the parent management CRUD feature in SchoolFlow's gestionnaire interface. Use when verifying parent create/read/update/delete flows or child assignment logic.
---

# Testing SchoolFlow Parent Management

## Prerequisites

- PHP 8.4+ and Composer installed
- SQLite database at `database/database.sqlite`
- Laravel server running: `php artisan serve --host=0.0.0.0 --port=8000`

## Devin Secrets Needed

None required — uses local SQLite database with seeded test data.

## Setup

1. **Install dependencies**: `composer install`
2. **Run migrations**: `php artisan migrate`
3. **Seed test data** (if empty DB):
   ```bash
   php artisan tinker --execute="
     \App\Models\User::create(['name'=>'Admin Test','email'=>'admin@test.com','password'=>bcrypt('password123'),'role'=>'gestionnaire']);
     \$c1 = \App\Models\Classe::create(['nom'=>'CP1','niveau'=>'CP1','frais_scolarite'=>50000]);
     \$c2 = \App\Models\Classe::create(['nom'=>'CE1','niveau'=>'CE1','frais_scolarite'=>55000]);
     \App\Models\Eleve::create(['matricule'=>'E001','nom_complet'=>'Ouedraogo Awa','classe_id'=>\$c1->id,'date_naissance'=>'2018-01-01','genre'=>'Féminin','statut'=>'actif']);
     \App\Models\Eleve::create(['matricule'=>'E002','nom_complet'=>'Kabore Ibrahim','classe_id'=>\$c1->id,'date_naissance'=>'2017-06-15','genre'=>'Masculin','statut'=>'actif']);
     \App\Models\Eleve::create(['matricule'=>'E003','nom_complet'=>'Sore Fatimata','classe_id'=>\$c2->id,'date_naissance'=>'2017-09-20','genre'=>'Féminin','statut'=>'actif']);
   "
   ```
4. **Start server**: `php artisan serve --host=0.0.0.0 --port=8000`
5. **Login**: Navigate to `http://localhost:8000/login` with `admin@test.com` / `password123`

## Known Issues and Workarounds

- **SQLite CHECK constraint on role column**: The original migration may only allow `['gestionnaire', 'enseignant']`. If parent creation fails with `CHECK constraint failed: role`, a migration is needed to recreate the users table with `['gestionnaire', 'enseignant', 'parent']`. SQLite does not support `ALTER COLUMN` for CHECK constraints — the table must be recreated (create temp, drop old, recreate with new schema, copy data).
- **Browser-level validation**: The create form uses HTML `required` attributes. To test Laravel server-side validation, you may need to bypass browser validation (e.g., remove `required` attribute via devtools or use curl).

## Test Cases

1. **Dashboard/Sidebar**: Verify "Parents" link in sidebar and "Gestion des parents" card on dashboard with parent count badge
2. **Create**: Fill form (name, email, password, phone, address), check child checkboxes, submit. Verify success message, table row, and child badges
3. **View (Show)**: Click eye icon. Verify parent info card and children table with matricules/classes/status
4. **Edit**: Change phone, reassign children (uncheck one, check another), leave password empty. Verify pre-filled form, success message, updated data
5. **Validation**: Submit empty create form. Verify browser required-field validation blocks submission
6. **Delete**: Click trash icon, accept confirm dialog. Verify success message, empty table, and that eleves still exist (navigate to eleves list)

## Tips

- The create form only shows **unassigned** eleves. The edit form shows **all** eleves with assigned ones pre-checked.
- After creating a parent, the dashboard badge should increment. After deleting, it should decrement.
- The delete action uses `nullOnDelete` foreign key — children's `parent_id` is set to NULL, not cascade-deleted.
- Routes are under `gestionnaire/parents` with middleware `['auth', 'role:gestionnaire']`.
