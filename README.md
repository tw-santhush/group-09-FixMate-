# FixMate - Local Service Directory Website

This is our group project for ITIC1282 (Skill Development Project I) at KDU. We are building a website to help people find local service professionals in Sri Lanka like plumbers, electricians, carpenters, and mechanics.

## Group 09 Members

- Nilupuli Dewage - D/ICT/26/0024 (Leader)
- Temiya Wishal – D/ICT/26/0038
- Dasun Sumudu – D/ICT/26/0058
- Tharusha Methmina – D/ICT/26/0009
- Semindi Abesinghe – D/ICT/26/0057

## What this project does

People in Sri Lanka often struggle to find reliable handymen. Most workers rely on paper posters or word of mouth. FixMate makes them visible online so customers can find them easily.

Users can search for technicians by district and service type. They can see ratings, phone numbers, and contact them directly.

## What we used

- PHP for the backend
- MySQL for the database
- HTML and CSS for the frontend
- XAMPP for local development

## How to run it locally

1. Install XAMPP and start Apache + MySQL
2. Copy this whole folder to `C:\xampp\htdocs\`
3. Open phpMyAdmin and create a database called `fixmatedb`
4. Import the `database.sql` file (if included)
5. Update `includes/config.php` with your database credentials
6. Go to `http://localhost/fixmate/` in your browser

## What's working so far

- Home page with all sections
- Responsive header (works on mobile)
- Database design with 5 tables
- All 25 districts and 15 service types added
- Navigation between pages

## What we are still working on

- User login and registration (backend)
- Technician registration form
- Search functionality for finding technicians
- Dashboard for technicians
- Admin panel

## Live version

We deployed it to InfinityFree. You can check it here:
http://fixmate.infinityfreeapp.com/

## Test logins

Admin:
- Email: admin@fixmate.com
- Password: admin123

Technician:
- Email: themiya@gmail.com
- Password: (the one we set)

Normal user:
- You can register from the site
