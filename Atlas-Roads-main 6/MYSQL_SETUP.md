# 🗄️ MySQL Setup with XAMPP - Atlas Roads

## ✅ Setup Completed

Your **Atlas Roads** project is now configured to use **MySQL** with **XAMPP**!

---

## 📋 Configuration Details

### **.env Configuration**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atlas_roads
DB_USERNAME=root
DB_PASSWORD=
```

### **Database Information**
- **Database Name:** `atlas_roads`
- **Character Set:** `utf8mb4`
- **Collation:** `utf8mb4_unicode_ci`

---

## 📊 Database Tables

The following tables were created:

| Table | Description |
|-------|-------------|
| `users` | Admin users (1 user seeded) |
| `books` | Book catalog (6 books seeded) |
| `password_resets` | Password reset tokens |
| `failed_jobs` | Failed queue jobs |
| `personal_access_tokens` | API tokens |
| `migrations` | Migration history |

---

## 📚 Sample Data

### **Admin User**
- **Email:** admin@softui.com
- **Password:** secret

### **Books (6 seeded)**
1. **Le Petit Prince** - Antoine de Saint-Exupéry (1943)
2. **1984** - George Orwell (1949)
3. **Les Misérables** - Victor Hugo (1862) - Unavailable
4. **Harry Potter à l'école des sorciers** - J.K. Rowling (1997)
5. **The Great Gatsby** - F. Scott Fitzgerald (1925)
6. **L'Étranger** - Albert Camus (1942)

All books include real cover images from Goodreads!

---

## 🔧 Migration Fix Applied

### **Issue Fixed**
The original migration used `year()` column type which doesn't support years before 1901 in MySQL.

### **Solution**
Changed `published_year` from:
```php
$table->year('published_year');
```

To:
```php
$table->unsignedSmallInteger('published_year');
```

This allows books from any year (including classics like Les Misérables from 1862).

---

## 🚀 How to Start the Application

### **1. Make sure XAMPP is running**
- Start **Apache**
- Start **MySQL**

### **2. Start Laravel Development Server**
```bash
php artisan serve
```

### **3. Access the Application**
- **Public Homepage:** http://localhost:8000
- **Admin Login:** http://localhost:8000/login
- **Dashboard:** http://localhost:8000/dashboard
- **Books Management:** http://localhost:8000/books

### **4. Login Credentials**
```
Email: admin@softui.com
Password: secret
```

---

## 💾 Useful MySQL Commands

### **Access MySQL CLI**
```bash
C:\xampp\mysql\bin\mysql.exe -u root
```

### **Use Atlas Roads Database**
```sql
USE atlas_roads;
```

### **Show All Tables**
```sql
SHOW TABLES;
```

### **View All Books**
```sql
SELECT id, title, author, published_year, is_available FROM books;
```

### **View All Users**
```sql
SELECT id, name, email FROM users;
```

### **Check Database Size**
```sql
SELECT 
    table_name AS 'Table',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE table_schema = 'atlas_roads';
```

---

## 🔄 Database Management Commands

### **Reset Database (Fresh Start)**
```bash
php artisan migrate:fresh --seed
```
⚠️ **Warning:** This will delete all data!

### **Run Migrations Only**
```bash
php artisan migrate
```

### **Seed Data Only**
```bash
php artisan db:seed
```

### **Seed Books Only**
```bash
php artisan db:seed --class=BookSeeder
```

### **Rollback Last Migration**
```bash
php artisan migrate:rollback
```

---

## 🔍 Verify Database with Laravel

### **Check Connection**
```bash
php artisan tinker
```

Then run:
```php
DB::connection()->getPdo();
echo "Connected to: " . DB::connection()->getDatabaseName();
```

### **Quick Stats**
```bash
php artisan tinker --execute="echo 'Books: ' . \App\Models\Book::count();"
php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count();"
```

---

## 🌐 Access phpMyAdmin

You can manage your database visually using phpMyAdmin:

**URL:** http://localhost/phpmyadmin

**Login:**
- Username: `root`
- Password: *(leave empty)*

Navigate to `atlas_roads` database to see all tables and data.

---

## 📁 Database Backup

### **Export Database**
```bash
C:\xampp\mysql\bin\mysqldump.exe -u root atlas_roads > backup.sql
```

### **Import Database**
```bash
C:\xampp\mysql\bin\mysql.exe -u root atlas_roads < backup.sql
```

---

## ⚠️ Common Issues & Solutions

### **Issue: "Access denied for user 'root'@'localhost'"**
**Solution:** Check if XAMPP MySQL is running and password is correct in `.env`

### **Issue: "Unknown database 'atlas_roads'"**
**Solution:** Create database manually:
```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE atlas_roads CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### **Issue: "SQLSTATE[HY000] [2002] No connection could be made"**
**Solution:** Make sure MySQL service is running in XAMPP Control Panel

### **Issue: Images not showing**
**Solution:** Create storage symlink:
```bash
php artisan storage:link
```

---

## 🎯 Next Steps

1. ✅ **Database is ready** - MySQL with 6 sample books
2. ✅ **Admin user created** - admin@softui.com / secret
3. ✅ **Run the server** - `php artisan serve`
4. ✅ **Visit homepage** - http://localhost:8000
5. ✅ **Login to admin** - http://localhost:8000/login
6. ✅ **Manage books** - http://localhost:8000/books

---

## 📞 Database Connection Info

If you need to connect external tools:

- **Host:** 127.0.0.1 (or localhost)
- **Port:** 3306
- **Database:** atlas_roads
- **Username:** root
- **Password:** *(empty)*
- **Charset:** utf8mb4

---

**Date:** October 2, 2025  
**Status:** ✅ MySQL Database Configured & Running  
**Records:** 6 Books, 1 User

---

**Enjoy your Atlas Roads Library Management System!** 📚🚀 