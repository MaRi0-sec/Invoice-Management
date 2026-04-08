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

* As a developer with a background in Cybersecurity, I have implemented several layers of protection:

* Mass Assignment Protection: Used $fillable white-listing to prevent unauthorized field injections.
* SQL Injection Prevention: Fully utilized Laravel's Eloquent ORM and Query Builder which use PDO parameter binding by default.
* XSS Protection: Enforced Blade template engine's automatic escaping for all user-generated content.
* CSRF Protection: Integrated Laravel’s built-in tokens to prevent Cross-Site Request Forgery.
* Authentication & Authorization: Implemented token-based authentication using Laravel Sanctum, along with a permission-based access control system to manage user roles and restrict actions based on privileges.

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


<img width="1919" height="945" alt="1" src="https://github.com/user-attachments/assets/6527db40-7cf3-4661-b559-52259cc32f23" />
<img width="1919" height="943" alt="2" src="https://github.com/user-attachments/assets/00f16831-3af2-4000-9662-76ccee50f7f0" />
<img width="1919" height="944" alt="3" src="https://github.com/user-attachments/assets/fc148de8-8507-458a-a236-9862027ab955" />
<img width="1919" height="943" alt="5" src="https://github.com/user-attachments/assets/b5c864c5-a9a3-4e5b-afb8-1c634f012361" />
<img width="1919" height="943" alt="6" src="https://github.com/user-attachments/assets/15a4897d-3737-43f8-bfee-48e0ebbb5834" />
<img width="1919" height="941" alt="7" src="https://github.com/user-attachments/assets/2d2dd82c-9658-4a42-bc02-0016923e30d7" />
<img width="1919" height="943" alt="8" src="https://github.com/user-attachments/assets/4c49a959-be06-49fd-9e32-480e701c4666" />
<img width="1919" height="940" alt="9" src="https://github.com/user-attachments/assets/0bc78bf5-95e6-4fa5-a95f-3388fbe4224c" />
<img width="1919" height="940" alt="10" src="https://github.com/user-attachments/assets/2e9d1960-db9e-4893-8fe0-6e68a9f34e26" />
<img width="1919" height="937" alt="11" src="https://github.com/user-attachments/assets/95578667-0cd6-4370-8fa7-61236ad0e669" />
<img width="1919" height="939" alt="12" src="https://github.com/user-attachments/assets/6d69af5a-a0b8-4e79-b13d-474e365bc57b" />
<img width="1919" height="933" alt="13" src="https://github.com/user-attachments/assets/6a3b40dd-4708-45b3-b63e-e64f18fea137" />
<img width="1919" height="937" alt="14" src="https://github.com/user-attachments/assets/a9261544-a847-4399-a1ce-0f14fa7e1c6e" />
<img width="1919" height="935" alt="15" src="https://github.com/user-attachments/assets/3f7a272c-dab5-4721-9be5-85f5b243aa61" />
<img width="1919" height="941" alt="16" src="https://github.com/user-attachments/assets/ac6e059d-ffe8-410a-a68d-ae7db316c7d8" />
<img width="1919" height="943" alt="17" src="https://github.com/user-attachments/assets/ae7ba5b5-5c2d-4765-af73-3e12bb33b1bf" />
<img width="1919" height="946" alt="18" src="https://github.com/user-attachments/assets/5ac9405b-ba19-435f-805a-8f7f98567414" />
<img width="1917" height="944" alt="19" src="https://github.com/user-attachments/assets/8d89147f-3b8a-4745-af76-cf0ae1853b66" />
<img width="1919" height="954" alt="20" src="https://github.com/user-attachments/assets/ac87f9d3-b490-409a-b776-199e97f909a3" />
<img width="1919" height="940" alt="21" src="https://github.com/user-attachments/assets/f50b7fe1-b902-445d-bf04-bc2dd16101f1" />
<img width="1919" height="940" alt="22" src="https://github.com/user-attachments/assets/bd941b72-10ca-4064-b3f9-53aa3a9e9ccb" />
<img width="1919" height="935" alt="23" src="https://github.com/user-attachments/assets/cfae909c-c60d-44c1-87a3-d1270cce9e29" />
<img width="1919" height="935" alt="24" src="https://github.com/user-attachments/assets/0257d33f-2805-40e1-9f5b-dff3782780b1" />
<img width="1919" height="939" alt="25" src="https://github.com/user-attachments/assets/205df6e1-c849-4ac8-b944-7e51fdb7ba3b" />


⚠️ Note: This project is currently under active development. I am working on adding new features and enhancing the security layers. Feel free to explore the code!
