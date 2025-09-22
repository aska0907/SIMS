# 📊 School Results Management System (Laravel + Filament)

This project is a Laravel application powered by **Filament Admin** for managing academic results, students, teachers, and subjects.  
It provides automated **report generation, ranking, grading, and PDF export** for both O-Level and A-Level students.

---

## 🚀 Features

### 🔹 Results & Reporting
- Record student marks (Tests, Mid-Term, Terminal).
- Auto-calculates **average scores and grades**.
- Supports both **O-Level** and **A-Level** grading systems.
- Generates **class rankings** with ties handled properly.
- **Division assignment** (O-Level).
- Export academic reports to **PDF** with one click.

### 🔹 Dashboard
- Visual summary with **graphs & charts** (students, advanced students, subjects, combinations, teachers).
- Powered by **Chart.js** for clean data visualization.
- Counts fetched via a **dedicated DashboardController**.

### 🔹 Student Management
- Manage **O-Level students**.
- Manage **A-Level (Advanced) students**.
- Assign **subjects & combinations** dynamically.

### 🔹 Teacher Management
- Manage teachers as `users` (role-based).
- Flexible for future roles (e.g., admin, parent).

---

## 🛠️ Tech Stack
- **Laravel** 10+
- **Filament Admin** (modern admin panel)
- **TailwindCSS** (styling)
- **Barryvdh DomPDF** (PDF export)
- **Chart.js** (graphs)
- **MySQL / PostgreSQL** (database)

---

## ⚙️ Installation

```bash
# Clone project
git clone https://github.com/yourusername/school-results.git
cd school-results

# Install dependencies
composer install
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Start server
php artisan serve
