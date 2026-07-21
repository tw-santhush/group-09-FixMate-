# FixMate - Local Service Professional Directory

## Project Overview

FixMate is a web-based directory that digitizes Sri Lanka's informal handyman/mechanic sector. Users can search for and connect with local service professionals (plumbers, electricians, carpenters, mechanics, painters, etc.) by district and service type.

**Module**: ITIC1282 - Skill Development Project  
**Institution**: KDU (Colombo)  
**Year**: 2026

---

## Features

### 👤 For Normal Users
- User registration and login
- Search for service professionals by district and service type
- View technician details (name, rating, phone, experience)
- Direct call and WhatsApp contact buttons
- Leave and view customer reviews (1-5 star ratings)
- User profile management

### 🔧 For Technicians/Service Professionals
- Complete registration form (job type, district, experience, bio)
- Technician login and personal dashboard
- View profile statistics (rating, jobs completed)
- Edit profile information
- View customer reviews and ratings

### 👨‍💼 For Administrators
- Admin login and control panel
- View all registered users and technicians
- Delete users or technicians (with cascade delete of reviews)
- View platform statistics (total users, total technicians)

### ⭐ Review System
- Users can rate technicians (1-5 stars)
- Leave written reviews with feedback
- View all reviews for each technician
- Edit existing reviews
- Automatic rating calculation

---

## Technology Stack

**Backend**: PHP 8.2.12  
**Database**: MySQL  
**Frontend**: HTML5, CSS3  
**Server**: Apache 2.4.58 (XAMPP)  
**Security**: Password hashing (PHP password_hash), session management, SQL injection prevention

---

## Database Structure

### Tables

1. **users** - Normal user accounts
   - id, email, password, name, phone, created_at

2. **technicians** - Service professional accounts
   - id, email, password, name, phone, district_id, service_id, experience, rating, jobs_completed, bio, created_at

3. **districts** - 25 Sri Lankan districts
   - id, district_name

4. **services** - 15 service types
   - id, service_name

5. **ratings** - Customer reviews
   - id, user_id, technician_id, rating (1-5), review, created_at

6. **admins** - Administrator accounts
   - id, email, password, created_at

---

## Installation & Setup

### Prerequisites
- XAMPP (Apache, MySQL, PHP)
- Web browser

### Steps

1. **Extract Files**

2. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

3. **Create Database**
   - Open http://localhost/phpmyadmin
   - Create database: `fixmate_db`
   - Import SQL (or run queries in SQL tab)

4. **Access Application**

### Initial Credentials

**Normal User** (for testing):
- Email: testuser@example.com
- Password: password123

**Technician** (for testing):
- Email: ravi.tech@example.com
- Password: password123

**Admin** (for testing):
- Email: admin@fixmate.com
- Password: admin123

---

## Project Structure

---

## Key Features Implemented

✅ **User Authentication**
- Secure password hashing (PHP password_hash)
- Session-based login management
- Role-based access control (Normal User, Technician, Admin)
- Logout functionality

✅ **Search & Filter**
- Filter by district (25 districts)
- Filter by service type (15 services)
- Sorted by rating (highest first)
- Display phone number and contact options

✅ **Contact Methods**
- Direct phone call links (tel://)
- WhatsApp direct messaging links
- Proper phone number formatting (+94 format)

✅ **Review System**
- 1-5 star ratings
- Text reviews with feedback
- Auto-calculation of technician ratings
- Edit existing reviews
- View all reviews for each technician

✅ **Data Management**
- User registration and profiles
- Technician profiles with experience/bio
- Admin management (create, read, delete)
- Cascade delete (removes reviews when user/tech deleted)

✅ **Security**
- SQL injection prevention (mysqli_real_escape_string)
- Password hashing (password_hash, password_verify)
- Session management
- Role-based access control
- CSRF protection on delete operations

---

## User Workflows

### Normal User Flow
1. Register → Login → Search → View Results → Contact Pro → Leave Review

### Technician Flow
1. Register → Login → View Dashboard → Edit Profile → Receive Reviews

### Admin Flow
1. Login → View Stats → Manage Users/Technicians → Delete if needed

---

## Sample Data

The database includes:
- 2 normal user accounts
- 20+ technician profiles across multiple districts
- All 25 Sri Lankan districts
- 15 service types
- Sample reviews and ratings

---

## Testing Checklist

- [x] User registration and login works
- [x] Technician registration and login works
- [x] Admin login works
- [x] Search filters correctly
- [x] Phone/WhatsApp links work
- [x] Reviews can be added and edited
- [x] Ratings auto-update
- [x] Delete operations work (cascade delete)
- [x] Session management works
- [x] Error messages display correctly
- [x] Navigation works between pages

---

## Known Limitations

- No image upload for technician profiles
- No email verification
- No password reset functionality
- No real-time notifications
- No messaging between users and technicians
- Admin password stored in database (not hashed initially, now using password_hash)

---

## Future Enhancements

- Technician profile pictures
- Email verification during registration
- Password reset via email
- Real-time chat/messaging
- Payment/booking system
- Mobile app
- SMS notifications
- Advanced search filters (experience level, price range)

---

## Troubleshooting

**Issue**: Blank page  
**Solution**: Check PHP error logs, ensure Apache/MySQL are running

**Issue**: Login fails  
**Solution**: Verify email exists in database, check password spelling

**Issue**: Phone links don't work  
**Solution**: Ensure phone numbers are in correct format (0712345678)

**Issue**: Reviews don't appear  
**Solution**: Ensure user is logged in, refresh page

---

## Author Notes

This project was built as a Skill Development Project (ITIC1282) at KDU. It demonstrates:
- Full-stack web development (frontend + backend + database)
- User authentication and role-based access control
- Database design and relationships
- Security best practices
- CRUD operations
- Responsive web design

---

## License

Educational Project - KDU ITIC1282

---

## Contact

For questions about this project, contact the development team.

**Project Completion Date**: July 2026
