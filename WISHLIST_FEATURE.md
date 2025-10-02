# 📚 Wishlist Feature Documentation / دليل ميزة قائمة الرغبات

## Overview / نظرة عامة

**English:**
The Wishlist feature allows users to request books that are not currently available in the library. Users can submit requests with book details, and administrators can manage these requests by searching for books, updating statuses, and eventually acquiring the books.

**العربية:**
تتيح ميزة قائمة الرغبات للمستخدمين طلب كتب غير متوفرة حالياً في المكتبة. يمكن للمستخدمين تقديم طلبات بتفاصيل الكتب، ويمكن للمسؤولين إدارة هذه الطلبات من خلال البحث عن الكتب وتحديث الحالات وفي النهاية الحصول على الكتب.

---

## Installation / التثبيت

### 1. Run Migrations / تشغيل الهجرات

```bash
php artisan migrate
```

This will create two tables:
- `wishlists` - Main wishlist requests table
- `wishlist_votes` - User voting on wishlist items

### 2. (Optional) Seed Sample Data / تعبئة بيانات تجريبية

```bash
php artisan db:seed --class=WishlistSeeder
```

---

## Features / الميزات

### For Users / للمستخدمين

#### ✅ Request Books / طلب الكتب
Users can submit requests for books they want by providing:
- Title (required) / العنوان (إجباري)
- Author (required) / المؤلف (إجباري)
- ISBN (optional) / الرقم التسلسلي (اختياري)
- Description/Reason (optional) / الوصف/السبب (اختياري)
- Priority (HIGH/MEDIUM/LOW) / الأولوية
- Maximum Price Budget (optional) / الميزانية القصوى (اختياري)

**Access:** `/wishlist/create`

#### 📋 View My Wishlist / عرض قائمتي
- See all your book requests
- Filter by status and priority
- Track request progress
- View admin notes and estimates

**Access:** `/wishlist`

#### 🗳️ Vote on Requests / التصويت على الطلبات
- Browse all public wishlist requests
- Vote on books you also want
- See most voted books

**Access:** `/wishlist-browse`

#### ⭐ Rate Service / تقييم الخدمة
After a book is found, users can:
- Rate the service (1-5 stars)
- Leave feedback

---

### For Admins / للمسؤولين

#### 📊 Dashboard / لوحة التحكم
View statistics:
- Total wishes
- Pending requests
- Success rate
- Most voted books
- High priority items

**Access:** `/admin/wishlist/dashboard`

#### 📝 Manage Requests / إدارة الطلبات
- View all wishlist requests
- Filter and search
- Update status (PENDING → SEARCHING → FOUND → ORDERED)
- Add admin notes
- Set estimated price and delivery time

**Access:** `/admin/wishlist`

#### 🔗 Link to Existing Books / ربط بالكتب الموجودة
When a requested book is already in the library:
- Search for similar books
- Link wishlist to existing book
- Notify user automatically

#### ➕ Create New Book / إنشاء كتاب جديد
Create a new book directly from wishlist request:
- Pre-fills title, author, ISBN
- Add additional details (category, language, year)
- Auto-links to wishlist request

---

## Request Statuses / حالات الطلبات

| Status | Arabic | Description |
|--------|--------|-------------|
| **PENDING** | قيد الانتظار | New request, not yet reviewed |
| **SEARCHING** | جاري البحث | Admin is actively searching for the book |
| **FOUND** | تم الإيجاد | Book found and available |
| **ORDERED** | تم الطلب | Book ordered from supplier |
| **REJECTED** | مرفوض | Cannot fulfill this request |

---

## Priority Levels / مستويات الأولوية

| Priority | Arabic | Icon | Use Case |
|----------|--------|------|----------|
| **HIGH** | عالية | 🔴 | Urgent, important books |
| **MEDIUM** | متوسطة | 🟡 | Regular interest |
| **LOW** | منخفضة | 🟢 | Nice to have |

---

## Routes / المسارات

### User Routes

```php
GET  /wishlist              // List user's wishes
GET  /wishlist/create       // Create new wish form
POST /wishlist              // Store new wish
GET  /wishlist/{id}         // View wish details
GET  /wishlist/{id}/edit    // Edit wish form
PUT  /wishlist/{id}         // Update wish
DELETE /wishlist/{id}       // Cancel wish
POST /wishlist/{id}/vote    // Toggle vote
POST /wishlist/{id}/feedback // Submit rating
GET  /wishlist-browse       // Browse all public wishes
```

### Admin Routes

```php
GET  /admin/wishlist/dashboard          // Admin dashboard
GET  /admin/wishlist                    // List all wishes
GET  /admin/wishlist/{id}               // View wish details
POST /admin/wishlist/{id}/update-status // Update status
POST /admin/wishlist/{id}/link-book     // Link to existing book
POST /admin/wishlist/{id}/create-book   // Create new book
POST /admin/wishlist/bulk-update        // Bulk status update
```

---

## Database Schema / مخطط قاعدة البيانات

### wishlists Table

```sql
- id
- user_id (foreign key to users)
- title (string, 255)
- author (string, 100)
- isbn (string, 20, nullable)
- description (text, nullable)
- priority (enum: LOW, MEDIUM, HIGH)
- status (enum: PENDING, SEARCHING, FOUND, ORDERED, REJECTED)
- admin_notes (text, nullable)
- book_id (foreign key to books, nullable)
- is_found (boolean)
- found_at (timestamp, nullable)
- estimated_price (decimal, nullable)
- estimated_days (integer, nullable)
- max_price (decimal, nullable)
- service_rating (1-5, nullable)
- feedback (text, nullable)
- created_at, updated_at
```

### wishlist_votes Table

```sql
- user_id (foreign key to users)
- wishlist_id (foreign key to wishlists)
- created_at, updated_at
- PRIMARY KEY (user_id, wishlist_id)
```

---

## API Endpoints (Future) / نقاط API (مستقبلاً)

The voting feature uses AJAX:

```javascript
POST /wishlist/{id}/vote

Response:
{
    "success": true,
    "voted": true,
    "votes_count": 5
}
```

---

## Usage Examples / أمثلة الاستخدام

### Create a Wishlist Request

```php
$wishlist = Wishlist::create([
    'user_id' => auth()->id(),
    'title' => 'Clean Code',
    'author' => 'Robert C. Martin',
    'isbn' => '978-0132350884',
    'priority' => 'HIGH',
    'max_price' => 50.00
]);
```

### Update Status (Admin)

```php
$wishlist->update([
    'status' => 'SEARCHING',
    'admin_notes' => 'Found supplier, checking availability',
    'estimated_price' => 45.99,
    'estimated_days' => 7
]);
```

### Mark as Found

```php
$wishlist->markAsFound($bookId);
// Sets status to FOUND, is_found = true, found_at = now()
```

### Vote on Wishlist

```php
$wishlist->toggleVote(auth()->id());
// Returns true if voted, false if un-voted
```

---

## Benefits / الفوائد

### For Library / للمكتبة
✅ Understand customer demand
✅ Make informed purchasing decisions
✅ Reduce unwanted inventory
✅ Increase customer satisfaction

### For Users / للمستخدمين
✅ Request hard-to-find books
✅ Track request progress
✅ Get notified when books arrive
✅ Influence library acquisitions

---

## Future Enhancements / تحسينات مستقبلية

- 📧 Email notifications for status updates
- 🔔 Real-time notifications
- 📊 Advanced analytics dashboard
- 🤖 Auto-search integration with book APIs
- 💳 Pre-payment/reservation system
- 🌐 Multi-language support
- 📱 Mobile app integration

---

## Support / الدعم

For questions or issues with the wishlist feature:
- Check the code comments
- Review the controller methods
- Examine the model relationships

---

## Credits / المساهمون

Feature developed for **Atlas Roads Library Management System**

Version: 1.0.0
Date: October 2025

---

Made with ❤️ for book lovers everywhere! 📚 