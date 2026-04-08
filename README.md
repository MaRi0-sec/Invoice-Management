# 🧾 Invoice Management System

A robust and secure Invoice Management System built with **Laravel**. This project is designed to streamline the process of creating, managing, and tracking invoices while maintaining high standards of data integrity and security.

---

## 🚀 Key Features

* **Invoice Lifecycle Management:** Create, Read, Update, and Delete (CRUD) invoices with status tracking (Paid, Unpaid, Partially Paid).
* **Automated Calculations:** Real-time calculation of taxes, discounts, and final totals to ensure financial accuracy.
* **Search & Filtering:** Advanced filtering options to find invoices by date, customer, or status.
* **Dashboard Analytics:** High-level overview of Invoices.

---

## 🛡️🔐 Security Implementation

* **As a developer with a background in Cybersecurity, I have implemented several layers of protection:

* **Mass Assignment Protection: Used $fillable white-listing to prevent unauthorized field injections.
* **SQL Injection Prevention: Fully utilized Laravel's Eloquent ORM and Query Builder which use PDO parameter binding by default.
* **XSS Protection: Enforced Blade template engine's automatic escaping for all user-generated content.
* **CSRF Protection: Integrated Laravel’s built-in tokens to prevent Cross-Site Request Forgery.
* **Authentication & Authorization: Implemented token-based authentication using Laravel Sanctum, along with a permission-based access control system to manage user roles and restrict actions based on privileges.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 10/11
* **Database:** MySQL
* **Frontend:** Blade Templates, Bootstrap/Tailwind
* **Tools:** Composer, Git

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally:

1. **Clone the repository:**
   
   git clone [https://github.com/MaRi0-sec/Invoice-Management.git](https://github.com/MaRi0-sec/Invoice-Management.git)
   cd Invoice-Management

2. Install dependencies:

    composer install
    npm install && npm run dev

3. Environment Setup:

   cp .env.example .env
   php artisan key:generate

4. Configure Database:
   Update your .env file with your database credentials.

5. Run Migrations & Seeders:

   php artisan migrate --seed

6. Start the Server:

   php artisan serve

⚠️ Note: This project is currently under active development. I am working on adding new features and enhancing the security layers. Feel free to explore the code!
