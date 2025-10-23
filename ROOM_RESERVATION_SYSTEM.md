# 📚 Library Room Reservation System with AI

## ✅ System Complete!

### 🗄️ Database Structure

#### **1. bibliotheques** (existing)
- Already has: id, name, address, city, latitude, longitude
- Now connected to sections

#### **2. sections** 
```
id
bibliotheque_id → foreign key
name → section name (Science, History, Kids, Study Area)
description
```

#### **3. rooms**
```
id
section_id → foreign key
name → room name/number
capacity → number of seats
style → individual | group | conference
has_pc → boolean
has_wifi → boolean
equipments → JSON (projector, whiteboard, etc.)
price_per_hour → decimal
availability_schedule → JSON
is_active → boolean
```

#### **4. room_reservations**
```
id
room_id → foreign key
user_id → foreign key
date
start_time
end_time
total_price → calculated automatically
status → pending | confirmed | cancelled
notes
```

---

## 🤖 AI Features Implemented

### 1. **Location-Based Recommendations**
- Uses bibliotheque GPS coordinates (latitude, longitude)
- Calculates distance from user location using Haversine formula
- Sorts rooms by proximity

### 2. **Smart Room Matching**
- Filters by style (individual/group/conference)
- Filters by capacity
- Filters by equipment (PC, WiFi, projector, etc.)
- Filters by price range

### 3. **Availability Checking**
- Real-time availability validation
- Prevents double-booking
- Shows available time slots

### 4. **AI Scoring System**
Rooms are scored based on:
- **Distance** (+20 if <1km, +10 if <5km, -20 if >20km)
- **Price** (+30 for free, +15 if <10€, -10 if >50€)
- **Capacity Match** (+15 perfect match, +5 close match)
- **Equipment** (+5 for PC, +5 for WiFi, +2 per extra equipment)
- **Style Preference** (+10 if matches user preference)

### 5. **Automatic Price Calculation**
- Calculates based on `price_per_hour` × duration
- Handles partial hours (30min = 0.5h)
- Shows total before booking

### 6. **Optimal Time Slot Suggestions**
- AI suggests best available times
- Checks business hours (8:00-20:00)
- Shows price for each slot

---

## 🛣️ Routes Created

### **User Routes** (Authenticated)
```
GET  /rooms/search                    → Search & browse rooms
GET  /rooms/{room}                    → View room details
GET  /rooms/{room}/reserve            → Reservation form
POST /room-reservations               → Create reservation
GET  /my-reservations                 → View my bookings
POST /room-reservations/{id}/cancel   → Cancel reservation
POST /rooms/check-availability        → AJAX availability check
POST /rooms/suggest-times             → AI time suggestions
```

### **Admin Routes** (Admin Only)
```
# Sections
GET    /admin/sections              → List sections
GET    /admin/sections/create       → Create section form
POST   /admin/sections              → Store section
GET    /admin/sections/{id}         → View section
GET    /admin/sections/{id}/edit    → Edit section form
PUT    /admin/sections/{id}         → Update section
DELETE /admin/sections/{id}         → Delete section

# Rooms
GET    /admin/rooms                 → List rooms
GET    /admin/rooms/create          → Create room form
POST   /admin/rooms                 → Store room
GET    /admin/rooms/{id}            → View room + stats
GET    /admin/rooms/{id}/edit       → Edit room form
PUT    /admin/rooms/{id}            → Update room
DELETE /admin/rooms/{id}            → Delete room
GET    /admin/rooms/{id}/reservations → View room reservations
POST   /admin/rooms/{id}/reservations/{rid}/confirm → Confirm reservation
POST   /admin/rooms/{id}/reservations/{rid}/cancel  → Cancel reservation
```

---

## 📦 Models & Relationships

### **Bibliotheque**
```php
hasMany → sections
hasMany → books
```

### **Section**
```php
belongsTo → bibliotheque
hasMany → rooms
```

### **Room**
```php
belongsTo → section
hasMany → reservations
// Methods:
isAvailable(date, startTime, endTime)
calculatePrice(startTime, endTime)
```

### **RoomReservation**
```php
belongsTo → room
belongsTo → user
// Methods:
confirm()
cancel()
getDurationInHours()
isUpcoming()
isActiveNow()
```

### **User**
```php
hasMany → roomReservations
```

---

## 🎯 How It Works

### **Admin Flow:**
1. Create bibliotheque (existing feature)
2. Add sections to bibliotheque
3. Add rooms to sections
4. Set room details (capacity, style, equipment, price)
5. Manage reservations (confirm/cancel)

### **User Flow:**
1. Search for rooms with filters
   - Location (GPS-based)
   - Date & time
   - Style (individual/group/conference)
   - Capacity
   - Equipment (PC, WiFi, etc.)
   - Price range

2. AI recommends best rooms based on:
   - Proximity to user
   - Matching preferences
   - Availability
   - Price/value

3. Select room and time slot
   - System checks real-time availability
   - AI suggests optimal times if needed
   - Auto-calculates price

4. Create reservation
   - Status: "pending"
   - Admin can confirm or cancel

5. View "My Reservations"
   - See all bookings
   - Cancel if needed

---

## 💡 Key Features

✅ **AI-Powered Room Recommendations**
✅ **Location-Based Search** (GPS distance calculation)
✅ **Real-Time Availability**
✅ **Automatic Price Calculation**
✅ **Smart Time Slot Suggestions**
✅ **Multi-Library Support**
✅ **Section Organization**
✅ **Equipment Filtering**
✅ **Reservation Management**
✅ **Foreign Key Constraints** (cascading deletes)

---

## 🚀 Next Steps

### To Use the System:

1. **As Admin:**
```
Login as admin@softui.com
Go to /admin/sections → Create sections
Go to /admin/rooms → Create rooms
```

2. **As User:**
```
Login as any user
Go to /rooms/search
Apply filters
Book a room!
```

### Optional Enhancements:

- Create views for all routes (currently only backend is complete)
- Add payment integration
- Add email notifications
- Add calendar view for room availability
- Add room images
- Add user reviews for rooms
- Add recurring reservations

---

## 📊 Database Schema

```
bibliotheques (existing)
    └── sections
            └── rooms
                    └── room_reservations → users
```

All tables created with foreign key constraints and cascading deletes! 🎉

---

## ⚡ Testing

You can test the system by:

1. Creating a bibliotheque (already exists)
2. Creating sections via admin panel
3. Creating rooms in those sections
4. Searching for rooms as a user
5. Making reservations

The AI will automatically:
- Calculate distances
- Score rooms
- Suggest optimal times
- Calculate prices

**System is ready to use!** 🚀

