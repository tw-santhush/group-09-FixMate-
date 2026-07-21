# FixMate — Comprehensive Analysis Report

**Project:** FixMate (Local Service Pro Directory)
**Location:** `C:\xampp\htdocs\Fixmate`
**Date:** July 20, 2026
**Scope:** Full code audit for bugs, security, UX, and code quality

---

## Table of Contents

1. [Critical Issues (Security & Data Integrity)](#1-critical-issues-security--data-integrity)
2. [Code Quality Issues](#2-code-quality-issues)
3. [UX / Functionality Issues](#3-ux--functionality-issues)
4. [Missing Features](#4-missing-features)
5. [Edge Case Analysis](#5-edge-case-analysis)
6. [File-by-File Review Summary](#6-file-by-file-review-summary)
7. [Priority Recommendations](#7-priority-recommendations)

---

## 1. Critical Issues (Security & Data Integrity)

### 1.1 SQL Injection Risk — No Prepared Statements
**Severity: HIGH**

All 15 PHP files use `mysqli_real_escape_string()` with direct string interpolation. While this prevents basic injection, prepared statements are the standard for parameterized queries.

**Affected files:** Every file that queries the database.

**Example (login.php:15):**
```php
$query = "SELECT id, name, password FROM users WHERE email = '$email'";
```

### 1.2 Admin Delete Doesn't Recalculate Ratings
**Severity: HIGH**

**File:** `admin_panel.php:17-21` / `admin_panel.php:30-34`

When an admin deletes a user, all their ratings are removed, but the affected technicians' `rating` column is NEVER recalculated via `AVG()`. This causes inflated ratings that include reviews from now-deleted users.

**Steps to reproduce:**
1. User A gives Technician B a 1-star review.
2. Admin deletes User A.
3. Technician B's rating STILL reflects that 1-star review (or the average isn't recalculated from remaining ratings).

**Fix:** Add rating recalculation after deleting user/technician:
```php
// After deleting ratings for a technician, recalculate:
$avg = mysqli_query($conn, "SELECT AVG(rating) FROM ratings WHERE technician_id = '$tech_id'");
$new_rating = mysqli_fetch_assoc($avg)['avg_rating'];
mysqli_query($conn, "UPDATE technicians SET rating = '$new_rating' WHERE id = '$tech_id'");
```

### 1.3 Technician Edit Profile Missing Phone Normalization
**Severity: HIGH**

**File:** `edit_profile.php:30`

The registration form normalizes phone numbers to `94xxxxxxxxx` format, but the edit profile form in `edit_profile.php` stores the phone AS-IS with no `preg_replace()` normalization. This creates inconsistent phone formats in the database.

**Registration (register_technician.php:16-23):**
```php
$phone = preg_replace('/[^0-9]/', '', $phone_input);
if (substr($phone, 0, 2) == '94') { ... }
else if (substr($phone, 0, 1) == '0') { $phone = '94' . substr($phone, 1); }
else { $phone = '94' . $phone; }
```

**Edit (edit_profile.php:30) — NO normalization:**
```php
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
```

### 1.4 Cross-Table Email Collision
**Severity: MEDIUM**

**Files:** `register.php:24-28`, `register_technician.php:39-43`

- `register.php` checks only the `users` table for duplicate emails.
- `register_technician.php` checks only the `technicians` table.
- A user and a technician CAN register with the SAME email address.
- No `UNIQUE` constraint exists in the database schema (no `.sql` file was provided to verify).

### 1.5 No Server-Side Email Validation
**Severity: MEDIUM**

**Files:** `register.php`, `register_technician.php`

All registration forms rely solely on HTML5 `<input type="email">` for client-side validation. If client-side validation is bypassed (via curl, Postman, or browser dev tools), arbitrary strings like `"notanemail"` or `""` can be stored.

### 1.6 Duplicate District IDs in Dropdowns
**Severity: HIGH**

**Files:** `register_technician.php:101-126`, `find.php:46-71`

Both files have duplicate districts assigned different IDs:

| District   | Duplicate IDs | File(s)                |
|------------|---------------|------------------------|
| Matara     | 5 and 22      | register_technician.php, find.php |
| Kalutara   | 3 and 23      | register_technician.php, find.php |

Additionally, `edit_profile.php:91-113` is missing IDs 22, 23, and 24 entirely (only goes up to 21 then jumps to 25 for Negombo).

**Impact:** Selecting "Matara" could insert `district_id = 5` or `district_id = 22` depending on which option the user picks, causing incorrect district association.

---

## 2. Code Quality Issues

### 2.1 Massive CSS Duplication
**File:** `css/style.css`

`.error-message`, `.success-message`, and `.success-message a` are defined TWICE:
- Lines 779–803 (first definition)
- Lines 1383–1407 (identical duplicate)

### 2.2 Hardcoded Dropdowns in 3+ Files
**Files:** `find.php`, `register_technician.php`, `edit_profile.php`

District and service type dropdown options are hardcoded in every file. Any change (adding/removing a district) must be replicated across all files. These should be:
- Fetched from the `districts` and `services` tables in the database, OR
- Stored in a shared include file.

### 2.3 Unused Variable
**File:** `login_technician.php:8`

```php
$success = '';
```
This variable is declared but never assigned any success message or displayed. No success path exists on the technician login page.

### 2.4 Inline Styles in Production Code
**Files:** `add_review.php:125`, `edit_profile.php:150`, `edit_user_profile.php:83`

```php
style="display: flex; gap: 12px;"
```

These should be defined as CSS classes rather than inline styles for maintainability.

### 2.5 Session Key Naming Inconsistency
Different user types use differently-patterned session keys:
- `$_SESSION['user_id']` / `$_SESSION['user_name']` (normal users)
- `$_SESSION['tech_id']` / `$_SESSION['tech_name']` (technicians)
- `$_SESSION['admin_id']` / `$_SESSION['admin_email']` (admins)

This inconsistency makes session management harder to maintain and debug.

### 2.6 Phone Display Without Formatting
**File:** `user_profile.php:51`

Phone numbers are displayed as raw `94xxxxxxxxx` without any user-friendly formatting (e.g., `+94 XX XXX XXXX`).

### 2.7 Missing Database Schema File
No `.sql` dump or migration file exists in the project. Developers have no reference for table structures, column types, foreign keys, or indexes.

### 2.8 No `.gitignore`
If this project is tracked with Git, there's no `.gitignore` file. Temporary files, IDE config, or environment details may be accidentally committed.

---

## 3. UX / Functionality Issues

### 3.1 Nav Links Visible to Unauthenticated Users
**File:** `header.php:19-22`

```
<a href="dashboard.php">Technician Dashboard</a>
<a href="admin_panel.php">Admin Panel</a>
```

These links are shown to EVERY visitor, including guests who aren't logged in. While clicking them redirects to login, showing them creates a confusing experience.

### 3.2 No Redirect-Back Support on Login Pages
**Files:** `login.php`, `login_technician.php`, `login_admin.php`

After login, users always go to a fixed destination:
- `login.php` → `home.php`
- `login_technician.php` → `dashboard.php`
- `login_admin.php` → `admin_panel.php`

**Problem:** `add_review.php:10` passes `?redirect=find.php` in the URL, but no login page reads a `redirect` query parameter. Users who click "Review" while logged out are sent to login, then after logging in they land on `home.php` instead of returning to `find.php`.

### 3.3 Rating Pre-fill Bug After New Review
**File:** `add_review.php:77`

After a NEW review is submitted via POST, `$existing_review` was evaluated at line 34 (BEFORE the POST processing). Since it was false pre-POST, the pre-fill block at line 77 is skipped. The form resets to empty — the user sees an empty form with only a success message, which could be confusing.

### 3.4 No Email Change or Password Change
**File:** `edit_user_profile.php:75`

- The email field is `disabled` (never submitted).
- Neither `edit_profile.php` nor `edit_user_profile.php` offers a password change option.
- Users and technicians are stuck with their original credentials.

### 3.5 No Error Handling for Failed Queries
**Affects:** Multiple files

Most pages do not check `if ($result)` before calling `mysqli_fetch_assoc()`. If a query fails (e.g., database gone, syntax error), the page continues executing with PHP warnings and potentially broken output.

### 3.6 Review Count vs. Rating Count Mismatch
**File:** `view_reviews.php:29`

```php
WHERE r.technician_id = '$tech_id' AND r.review IS NOT NULL AND r.review != ''
```

The reviews display filters out rows where the `review` text is empty/NULL. However, the technician's overall rating (from the `technicians` table) is calculated from ALL ratings — including text-less ones. The displayed count `count($reviews)` may be lower than the actual number of ratings influencing the average.

### 3.7 No Logout Confirmation
**File:** `logout.php`

Clicking "Logout" instantly destroys the session and redirects. There's no confirmation dialog or "Are you sure?" prompt. Accidental clicks force users to log in again.

### 3.8 WhatsApp Link Missing Name Context
**File:** `find.php:141`

```php
https://wa.me/<?php echo $phone_intl; ?>?text=Hi%20I%20need%20your%20service
```

The WhatsApp message template is generic. It could include the technician's name or service type for a more personalized message:
```
Hi, I'm contacting you from FixMate regarding your [service] services.
```

---

## 4. Missing Features

### 4.1 No CSRF Protection
No form includes a CSRF token. An attacker could craft a malicious page that, when visited by an authenticated admin, submits the `delete_user` or `delete_tech` POST requests without the admin's knowledge.

### 4.2 No Rate Limiting on Login
Login pages have no throttle. An attacker can brute-force passwords with unlimited attempts.

### 4.3 No Logging System
Failed login attempts, deleted accounts, and database errors are not logged anywhere. Debugging production issues requires code inspection.

### 4.4 No "Forgot Password" Flow
There is no password reset mechanism. If a user forgets their password, they're locked out permanently with no recovery option.

### 4.5 No Search by Name or Keyword
**File:** `find.php`

The search only filters by district AND service. There's no free-text search for technician name, bio, or other keywords.

### 4.6 No Pagination
- **find.php:** If hundreds of technicians match, they all load on one page.
- **admin_panel.php:** Users and technicians tables grow without pagination.
- **view_reviews.php:** No pagination for reviews.

### 4.7 No Sort Options
Search results in `find.php` are sorted by rating DESC only. Users cannot sort by name, experience, or jobs completed.

### 4.8 No User Can Delete Own Account
There's no "Delete My Account" feature for normal users or technicians. Only an admin can delete accounts.

---

## 5. Edge Case Analysis

| Scenario | Current Behavior | Issue? |
|----------|-----------------|--------|
| User rates same technician twice | Update existing review (add_review.php:44-52) | OK |
| Admin deletes user with reviews | Reviews cascade-deleted; tech rating NOT recalculated | **BUG** |
| Admin deletes technician with reviews | Reviews cascade-deleted; no issue (tech gone) | OK |
| Technician changes service type | Old ratings stay on technician record | Acceptable |
| Direct access to protected page unauthenticated | Redirects to login | OK |
| Search returns 0 results | Shows "No technicians found" message (find.php:154) | OK |
| Empty/whitespace password | Checked via `$password` truthiness validation | **Weak** (password `"0"` would pass) |
| Extremely long name/bio input | No maxlength on server or client | **Potential DB truncation** |
| Technician registers with district_id that doesn't exist | Stored as-is; will cause broken JOIN in display | **Potential data issue** |
| Same user registers as both normal AND technician | Possible (email not checked across tables) | **BUG** |
| SQL injection via GET `tech_id` parameter | Escaped with `mysqli_real_escape_string()` | Partial protection |
| XSS via review text | `htmlspecialchars()` used on output | OK |
| Formula/script in bio or review text | `htmlspecialchars()` used on output | OK |

---

## 6. File-by-File Review Summary

### `includes/config.php`
- `session_start()` is always called (even on public pages) ✓
- No connection error beyond `die()` — no graceful recovery
- No timezone set for `date()` functions used elsewhere

### `includes/header.php`
- Nav shows Dashboard/Admin links to all users (UX issue) ✗
- Uses `htmlspecialchars()` for user names ✓
- Hardcoded `🔧` emoji in logo — works but not customizable

### `index.php`
- Simple redirect to `pages/home.php` ✓

### `pages/home.php`
- `register_technician.php` link at bottom ✓
- Static content, no dynamic elements ✓
- No login status awareness (always shows generic hero) — minor

### `pages/login.php`
- `password_verify()` used correctly ✓
- No `?redirect=` parameter support ✗
- No rate limiting ✗

### `pages/login_technician.php`
- Unused `$success` variable declared ✗
- Same issues as login.php

### `pages/login_admin.php`
- Same issues as login.php

### `pages/register.php`
- `password_hash()` used correctly ✓
- Email uniqueness check only in `users` table ✗
- No server-side email format validation ✗
- Minimum password length 6 — weak but functional

### `pages/register_technician.php`
- Phone normalization works ✓
- District/Services select options are hardcoded ✗
- Duplicate district IDs (Matara 5/22, Kalutara 3/23) ✗
- Same email validation gaps as register.php

### `pages/find.php`
- Results display with all key info ✓
- WhatsApp link uses international format ✓
- "No results" message ✓
- No pagination or sorting ✗
- District dropdown has duplicate IDs ✗

### `pages/dashboard.php`
- Auth check present ✓
- Correct JOINs and data display ✓
- Links to edit_profile.php ✓

### `pages/edit_profile.php`
- Auth check present ✓
- Form pre-fills correctly ✓
- Missing phone normalization ✗
- Missing 3 district options (22, 23, 24) ✗

### `pages/edit_user_profile.php`
- Auth check present ✓
- Email field disabled (no change possible) ✗
- No password change option ✗

### `pages/user_profile.php`
- Auth check present ✓
- Displays all user info ✓

### `pages/add_review.php`
- Duplicate review prevention ✓
- Rating recalculation after submission ✓
- Rating validated 1-5 ✓
- Pre-fill logic fragile after POST ✗

### `pages/view_reviews.php`
- JOINs to show user name, ratings, dates ✓
- Filters out empty review text (count mismatch) ✗
- No pagination ✗

### `pages/admin_panel.php`
- Stats display correct ✓
- Delete buttons work with JS confirm ✓
- No rating recalculation after user delete ✗
- No CSRF protection on delete forms ✗

### `pages/logout.php`
- `session_destroy()` called ✓
- Redirect works ✓
- No confirmation prompt ✗

### `css/style.css`
- Responsive design ✓
- Duplicate `.error-message` / `.success-message` blocks ✗
- Some colors hardcoded with no CSS variables ✗

---

## 7. Priority Recommendations

### HIGH (Must Fix)

| # | Issue | File(s) | Estimated Effort |
|---|-------|---------|-----------------|
| 1 | Migrate to prepared statements | All query files | 4-6 hours |
| 2 | Recalculate ratings after admin deletes user | `admin_panel.php` | 30 min |
| 3 | Normalize phone in edit_profile.php | `edit_profile.php` | 15 min |
| 4 | Fix duplicate district IDs | `register_technician.php`, `find.php`, `edit_profile.php` | 30 min |
| 5 | Add server-side email validation | `register.php`, `register_technician.php` | 30 min |
| 6 | Add DB unique constraints on emails | Schema | 15 min |

### MEDIUM (Should Fix)

| # | Issue | File(s) | Estimated Effort |
|---|-------|---------|-----------------|
| 7 | Add CSRF tokens to all POST forms | All forms | 2 hours |
| 8 | Add password change to edit profiles | `edit_profile.php`, `edit_user_profile.php` | 1 hour |
| 9 | Support `?redirect=` on all login pages | `login.php`, `login_technician.php`, `login_admin.php` | 1 hour |
| 10 | Fetch districts/services from DB instead of hardcoding | `find.php`, `register_technician.php`, `edit_profile.php` | 2 hours |
| 11 | Add pagination to search results and admin tables | `find.php`, `admin_panel.php` | 1-2 hours |
| 12 | Add rate limiting to login pages | All login pages | 1 hour |

### LOW (Nice to Have)

| # | Issue | File(s) | Estimated Effort |
|---|-------|---------|-----------------|
| 13 | Remove duplicate CSS blocks | `style.css` | 15 min |
| 14 | Hide dashboard/admin nav from guests | `header.php` | 15 min |
| 15 | Move inline styles to CSS classes | `add_review.php`, `edit_profile.php`, `edit_user_profile.php` | 30 min |
| 16 | Add logout confirmation | `logout.php` | 15 min |
| 17 | Add free-text search on find page | `find.php` | 1 hour |
| 18 | Add "Forgot Password" flow | New pages | 2-3 hours |
| 19 | Add `.gitignore` | Root | 5 min |
| 20 | Add database schema file (.sql) | Root | 30 min |

---

## File Checklist Summary

| File | Auth | SQL Safety | Validation | UX | Notes |
|------|------|-----------|------------|-----|-------|
| config.php | N/A | N/A | N/A | N/A | Basic MySQLi setup |
| header.php | Partial | N/A | N/A | ✗ Nav visible to all | |
| index.php | N/A | N/A | N/A | ✓ | Simple redirect |
| home.php | N/A | N/A | N/A | ✓ | Static content |
| login.php | ✓ | △ | ✗ | △ | No rate limit, no redirect param |
| login_technician.php | ✓ | △ | ✗ | △ | Unused var, same gaps |
| login_admin.php | ✓ | △ | ✗ | △ | Same gaps |
| register.php | ✓ | △ | ✗ | ✓ | No email format check |
| register_technician.php | ✓ | △ | ✗ | ✓ | Duplicate district IDs |
| find.php | N/A | △ | N/A | △ | No pagination, duplicate IDs |
| dashboard.php | ✓ | ✓ | N/A | ✓ | |
| edit_profile.php | ✓ | ✓ | ✗ | ✗ | Missing phone normalization, missing districts |
| edit_user_profile.php | ✓ | ✓ | ✗ | ✗ | No password change |
| user_profile.php | ✓ | ✓ | N/A | ✓ | |
| add_review.php | ✓ | ✓ | ✓ | △ | Pre-fill bug after POST |
| view_reviews.php | N/A | ✓ | N/A | △ | Count/rating mismatch |
| admin_panel.php | ✓ | ✓ | N/A | △ | No rating recalc, no CSRF |
| logout.php | N/A | N/A | N/A | △ | No confirmation |
| style.css | N/A | N/A | N/A | ✓ | Duplicate blocks |

**Legend:** ✓ Good / △ Needs Improvement / ✗ Broken or missing / N/A Not applicable

---

*Report generated from manual code review. All line references are from the current codebase as of July 20, 2026.*
