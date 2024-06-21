# Project Name

This project is a Laravel 10+ Vue.js 3 application that interacts directly with the Zoho CRM API through a web form.

## Prerequisites

Before you begin, ensure you have the following installed:
- PHP (>= 7.x)
- Composer
- Node.js (>= 14.x)
- npm or yarn

## Installation

1. **Clone the repository:**

   ```bash
   git clone <repository-url>
   cd <project-folder>
2. **Install PHP dependencies:**
   ```bash
   composer install
3. **Install JavaScript dependencies:**
   ```bash
   npm install   # or `yarn install`
4. **Copy the environment file:**
   ```bash
   cp .env.example .env
5. **Generate application key:**
   ```bash
   php artisan key:generate
## Configuration
1. **Set up Zoho CRM API credentials:**
   In your .env file, add the following lines and fill in your Zoho CRM API credentials obtained from Zoho Developer Console:
   ```bash
   ZOHO_CLIENT_ID=
   ZOHO_CLIENT_SECRET=
   ZOHO_REFRESH_TOKEN=
   
## Running the Application
1. **Compile assets and start Laravel server:**
   ```bash
   npm run dev
   php artisan serve
2. **Access the application:**
   Open your web browser and visit http://localhost:8000 (or as indicated by Laravel's php artisan serve command).

## Contribution
This web form app was written via Laravel Vue3. This web form integrate with ZOHO zrm api, so with this app u can create deals and account for your crm
