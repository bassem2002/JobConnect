# 💼 JobConnect

<p align="center">
  <img src="https://img.shields.io/badge/Laravel%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Tailwind%20CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Recruitment-Candidate%20%7C%20Company%20%7C%20Admin-0F766E?style=for-the-badge" alt="Recruitment platform" />
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-14B8A6?style=for-the-badge" alt="MIT License" /></a>
</p>

<p align="center"><a href="#-features">Features</a> · <a href="#️-software-architecture">Architecture</a> · <a href="#-installation">Installation</a> · <a href="#-testing">Testing</a></p>

### Full-Stack Recruitment & Job Management Platform

JobConnect is a full-stack recruitment platform built with **Laravel 12**, **PHP 8.2**, **MySQL**, **Blade**, **Tailwind CSS**, and **Alpine.js**.

The platform connects job seekers with companies through dedicated role-based workflows for **Candidates**, **Companies**, and **Administrators**. It supports job publishing and moderation, applications with CV management, candidate search, notifications, saved jobs, reporting, and administrative analytics.

---

## ✨ Features

### 👤 Candidate

Candidates can:

- Create and manage a personal profile
- Browse available job offers
- Search and filter job opportunities
- View detailed job descriptions
- Save interesting offers to favorites
- Apply using their profile CV or upload another CV
- Attach an optional cover letter
- Track application status
- Receive application status notifications
- Report problematic companies

Application statuses include:

- `Pending`
- `Accepted`
- `Rejected`

---

### 🏢 Company / Recruiter

Companies can:

- Create a recruiter/company account
- Manage company profile information
- Submit job offers
- Edit existing offers
- Archive and restore job postings
- Monitor applications received for each offer
- View candidate profiles
- Download submitted CVs and cover letters
- Accept or reject applications
- Add feedback when processing applications
- Search the candidate pool using multiple filters
- Report problematic candidate profiles

New companies require administrator validation before accessing all recruiter functionality.

---

### 🛡️ Administrator

Administrators can:

- Access a dedicated administration dashboard
- Validate newly registered companies
- Block users
- Moderate pending job offers
- Approve or reject job postings
- Manage job categories
- Review user reports
- Monitor platform activity
- View recruitment statistics
- Analyze candidate/company distribution
- Monitor application statistics
- Export platform statistics as CSV

---

## 🔎 Search & Filtering

JobConnect provides multi-criteria job search using Eloquent queries.

Available criteria include:

- Keyword
- Job title
- Location
- Category
- Contract type
- Required education level
- Company name

Recruiters can also search candidate profiles using criteria such as:

- Name
- City
- Professional domain
- Experience
- Education

---

## 📄 Job Application Workflow

```text
Candidate
   │
   ▼
Browse Job Offers
   │
   ▼
Select an Offer
   │
   ▼
Submit Application
   │
   ├── Profile CV
   ├── Custom CV
   └── Optional Cover Letter
   │
   ▼
Company Reviews Application
   │
   ├── Accept
   └── Reject
   │
   ▼
Candidate Receives Notification
```

When a candidate is accepted, the available vacancy count of the corresponding job offer is updated automatically.

---

## 🏗️ Software Architecture

JobConnect follows a traditional **Laravel MVC architecture**.

```text
Browser
   │
   ▼
Laravel Routes
   │
   ▼
Middleware
   │
   ▼
Controllers
   │
   ├──────────────► Blade Views
   │
   ▼
Eloquent Models
   │
   ▼
MySQL Database
```

### Architecture Components

**Models**

Eloquent models represent the application's domain entities and database relationships.

**Views**

Blade templates provide server-side rendered interfaces styled with Tailwind CSS and enhanced with Alpine.js.

**Controllers**

Controllers manage HTTP requests, validation, application workflows, database operations, file handling, and response rendering.

**Middleware**

Custom middleware controls authentication, user status, company validation, administration access, and navigation tracking.

---

## 🧩 Design Patterns

The project uses several architectural and framework patterns.

### MVC — Model View Controller

Laravel separates application responsibilities between:

```text
Model      → Eloquent ORM
View       → Blade Templates
Controller → HTTP Controllers
```

### Active Record

Eloquent models represent database records and provide query and persistence operations.

### Middleware / Intercepting Filter

Custom middleware is used for request-level authorization and validation.

Examples include:

```text
AdminMiddleware
CompanyMiddleware
CheckValidation
CheckUserStatus
TrackNavigation
```

### Front Controller

Laravel routes all HTTP requests through its central application entry point.

### Observer / Event Mechanisms

Laravel events and database notifications are used to react to application actions.

### View Composer

A custom `NavBadgesComposer` injects notification and navigation information into shared views.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| ORM | Eloquent ORM |
| Authentication | Laravel Breeze |
| Frontend | Blade |
| Styling | Tailwind CSS 3 |
| Client Interactivity | Alpine.js |
| HTTP Client | Axios |
| Build Tool | Vite |
| Database | MySQL |
| Notifications | Laravel Database Notifications |
| Dependency Management | Composer / NPM |

---

## 🔐 Authentication & Role Management

Authentication is implemented using **Laravel Breeze** with Laravel's session-based authentication system.

Three application roles are supported:

| Role | Main Responsibilities |
|---|---|
| Candidate | Search jobs, apply, manage CVs and track applications |
| Company | Publish offers, search candidates and process applications |
| Admin | Moderate users, companies, offers, categories and reports |

Role access is handled through:

- Laravel authentication middleware
- Custom role middleware
- Laravel Gates
- Controller-level ownership checks

Passwords are hashed using Laravel's hashing mechanisms.

---

## 🔔 Notifications

JobConnect uses Laravel database notifications.

Notifications are generated for events such as:

- New job application
- Application status update
- Newly approved job offer
- Report updates

The notification center allows users to view application-related updates directly from the platform.

---

## 📁 File Management

The application supports:

### Candidate documents

- PDF
- DOC
- DOCX
- CV uploads
- Cover letter uploads

### Company and category media

- JPEG
- PNG
- JPG
- GIF

Laravel's Storage system is used to manage uploaded files.

---

## 🗃️ Database Model

Main application entities include:

```text
User
 ├── JobOffers
 ├── Applications
 ├── SavedJobs
 └── Reports

Category
 └── JobOffers

JobOffer
 ├── Applications
 └── SavedJobs
```

### Main Models

- `User`
- `JobOffer`
- `Application`
- `Category`
- `SavedJob`
- `Report`

---

## 🔄 Main Recruitment Workflow

```text
Company Registration
        │
        ▼
Admin Validation
        │
        ▼
Create Job Offer
        │
        ▼
Pending Validation
        │
        ▼
Admin Approval
        │
        ▼
Published Job Offer
        │
        ▼
Candidate Application
        │
        ▼
Company Review
        │
        ├── Accepted
        └── Rejected
        │
        ▼
Candidate Notification
```

---

## 📊 Administration Dashboard

The administration dashboard provides indicators such as:

- Total users
- Number of candidates
- Number of companies
- Total job offers
- Total applications
- Total reports
- Pending job offers
- Open job offers
- Application conversion indicators
- User role distribution
- Category distribution
- City distribution
- Platform growth over recent months

Statistics can also be exported as a CSV file.

---

## 📂 Project Structure

```text
JobConnect/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   ├── Notifications/
│   ├── Providers/
│   └── View/
│
├── bootstrap/
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
│
├── storage/
├── tests/
│
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

---

## 🚀 Installation

### Prerequisites

Make sure you have installed:

- PHP >= 8.2
- Composer
- Node.js
- NPM
- MySQL

### 1. Clone the repository

```bash
git clone https://github.com/bassem2002/JobConnect.git
cd JobConnect
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Configure the environment

Create your local environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Configure your MySQL database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jobconnect
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 6. Create the storage symbolic link

```bash
php artisan storage:link
```

### 7. Build frontend assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 8. Start the application

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

---

## 🧪 Testing

The project contains the authentication and profile feature tests generated around the Laravel Breeze authentication workflow.

Run the test suite with:

```bash
php artisan test
```

Domain-specific automated tests for job offers, applications, and administration workflows are not currently included.

---

## 📸 Screenshots

Recommended screenshots for the repository:

### Job Board

![Job Board](docs/screenshots/job-board.png)

### Job Offer Details

![Job Offer Details](docs/screenshots/job-details.png)

### Candidate Applications

![Candidate Applications](docs/screenshots/candidate-applications.png)

### Recruiter Dashboard

![Recruiter Dashboard](docs/screenshots/recruiter-dashboard.png)

### Application Management

![Application Management](docs/screenshots/application-management.png)

### Admin Dashboard

![Admin Dashboard](docs/screenshots/admin-dashboard.png)

---

## ⚠️ Project Status

JobConnect was developed as an academic full-stack Laravel project and is intended primarily for learning, demonstration, and portfolio purposes.

The current architecture reflects the original implementation. Business logic is mainly handled directly inside Laravel controllers rather than through dedicated Service or Repository layers.

Additional security review and hardening are recommended before using the application in a production environment.

---

## 🔮 Possible Future Improvements

- Introduce Service and Repository layers for complex business logic
- Add Laravel Policies for centralized authorization
- Increase automated feature and integration test coverage
- Move sensitive candidate documents to private storage
- Add queued email notifications
- Enforce email verification
- Improve application auditing and activity logs
- Add REST API endpoints for mobile clients
- Containerize the application with Docker
- Add CI/CD with GitHub Actions

---

## 👨‍💻 Author

**Bassem Wali**

Software Engineering Student  
Full-Stack Development & Applied Artificial Intelligence

- GitHub: [@bassem2002](https://github.com/bassem2002)
- LinkedIn: [Bassem Wali](https://linkedin.com/in/bassem-wali)

---

## ⭐ About the Project

JobConnect demonstrates practical experience with:

- Laravel MVC architecture
- PHP backend development
- Eloquent ORM
- Relational database modeling
- Multi-role application workflows
- Authentication and authorization
- File upload management
- Recruitment workflow design
- Blade server-side rendering
- Tailwind CSS
- Alpine.js
- Search and filtering
- Database notifications
- Administrative dashboards
