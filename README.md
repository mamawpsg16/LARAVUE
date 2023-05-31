# Laravel 8 with Vue 3 Project

This is a Laravel 8 project integrated with Vue 3 for frontend development.

## Prerequisites

- PHP 8.1
- Composer
- Node.js and npm

## Clone the Repository

Use the following command to clone the repository:


## Install Dependencies

After cloning the repository with : git clone https://github.com/mamawpsg16/LARAVUE.git
install the project dependencies: 
- composer install 
- npm install 

## Set Up Environment Variables

1. Rename the `.env.example` file to `.env`.
2. Generate an application key by running the following command: php artisan key:generate

## Compile Assets

To compile the assets and enable Vue 3 support, run the following command: npm run dev

## Start the Development Server

Use the following command to start the Laravel development server: php artisan serve

## Generate Authorization Secretkey for JWTAuth
Use the following command to generate JWT_SECRET: php artisan jwt:secret


