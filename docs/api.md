# FeeTrack Customer App — API Documentation

**Base URL**: `http://your-domain.com/api`
**Content-Type**: `application/json`
**Auth**: JWT Bearer Token — `Authorization: Bearer <token>`

All endpoints are wrapped in the `force.json` middleware (always returns JSON, never HTML).

---

## Table of Contents

1. [Authentication (Public)](#1-authentication-public)
2. [Browse & Discovery (Public)](#2-browse--discovery-public)
3. [Listing Profile & Details (Public)](#3-listing-profile--details-public)
4. [Coupons & Billing Preview (Public)](#4-coupons--billing-preview-public)
5. [Visit Requests (Auth Required)](#5-visit-requests-auth-required)
6. [Booking & Payment Flow (Auth Required)](#6-booking--payment-flow-auth-required)
7. [Attendance — Punch In / Out (Auth Required)](#7-attendance--punch-in--out-auth-required)
8. [Customer KYC Profile (Auth Required)](#8-customer-kyc-profile-auth-required)
9. [Account Management (Auth Required)](#9-account-management-auth-required)
10. [Dashboard (Auth Required)](#10-dashboard-auth-required)
11. [Subscriptions (Auth Required)](#11-subscriptions-auth-required)
12. [Wallet (Auth Required)](#12-wallet-auth-required)

---

## 1. Authentication (Public)

### Request Registration OTP
`POST /register/request-otp`

Sends OTP to both the email and mobile number provided.

**Body**:
```json
{
  "name":   "John Doe",
  "email":  "john@example.com",
  "mobile": "9000000000"
}
```
**Response** `200 OK`:
```json
{ "status": "success", "message": "OTPs sent to your email and mobile. Please verify." }
```

---

### Resend Registration OTP
`POST /register/resend-otp`

Resend OTPs if the 10-minute session is still alive.

**Body**: `{ "email": "john@example.com", "mobile": "9000000000" }`

---

### Verify Registration & Create Account
`POST /register/verify`

Verifies both OTPs and creates the customer account.

**Body**:
```json
{
  "email":      "john@example.com",
  "mobile":     "9000000000",
  "email_otp":  "123456",
  "mobile_otp": "654321"
}
```
**Response** `201 Created`:
```json
{
  "status": "success",
  "data": {
    "user":  { "id": "uuid", "name": "John Doe", "email": "...", "mobile": "..." },
    "token": "eyJ0eXAiOiJKV1Qi...",
    "token_type": "bearer"
  }
}
```

---

### Request Login OTP
`POST /login/request-otp`

**Body**:
```json
{
  "mobile":  "9000000000",
  "channel": "sms"
}
```
- `channel` (optional): `sms` or `whatsapp`. If omitted, sends via both SMS and WhatsApp.

---

### Verify Login OTP
`POST /login/verify`

**Body**: `{ "mobile": "9000000000", "otp": "123456" }`

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "user":  { ... },
    "token": "eyJ0eXAiOiJKV1Qi...",
    "token_type": "bearer"
  }
}
```

---

## 2. Browse & Discovery (Public)

### Get App Banners
`GET /app-banners`

Returns home-screen promotional banners.

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `category_id` | uuid (optional) | If provided → global + category banners. If omitted → global only. |

**Response**:
```json
{
  "status": "success",
  "data": [
    { "id": 1, "title": "Summer Offer", "image_url": "https://...", "sort_order": 1 }
  ]
}
```

---

### Get Categories
`GET /categories`

Returns all active service categories (Gym, Dance, PG, etc.).

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1, "name": "Gym", "slug": "gym", "icon": "bi-bicycle",
      "has_shifts": true, "has_trainers": true,
      "has_rooms": false, "has_packages": true,
      "has_attendance": true
    }
  ]
}
```
> `has_attendance: true` means this category supports the daily punch-in/out system.

---

### Search & List Listings
`GET /listings`

Returns a paginated list of approved listings.

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `latitude` | float | Latitude of current location (for nearby sorting & 100km radius filter) |
| `longitude` | float | Longitude of current location |
| `search_latitude` | float | (Optional) Latitude of a explicitly searched location. Overrides `latitude`. |
| `search_longitude` | float | (Optional) Longitude of a explicitly searched location. Overrides `longitude`. |
| `radius_km` | float | Filter: only return listings within N km. **Defaults to 100 km** if lat & long are provided. |
| `category_id` | integer | Filter by category |
| `search` | string | OLX-style Keyword Search: Searches by listing Name (`title`) or Category name. |
| `location` | string | OLX-style Location Search: Searches by `city`, `state`, or `address`. **Note:** If provided, completely bypasses the GPS `latitude`/`longitude` radius filter. |
| `sort_dir` | string | Sort direction for distance: `asc` (default, nearest first) or `desc`. |

**Response** (paginated):
```json
{
  "status": "success",
  "data": {
    "current_page": 1, "total": 42,
    "data": [
      {
        "id": "uuid", "title": "Fitness First Gym", "address": "...",
        "distance_km": 1.23, "starting_price": 799,
        "images": [
          { "id": 1, "url": "https://...", "caption": null, "sort_order": 0 }
        ]
      }
    ]
  }
}
```

---

## 3. Listing Profile & Details (Public)

All endpoints use `{id}` = listing UUID.

### Listing Banners
`GET /listings/{id}/banners`

Returns all banner images for the listing.

```json
{
  "status": "success",
  "data": [
    { "id": 1, "url": "https://...", "caption": "Main hall", "sort_order": 0 }
  ]
}
```

---

### Listing Profile
`GET /listings/{id}/profile`

Full profile with distance, rating, images, partner info.

**Query Params**: `latitude`, `longitude` (optional, for distance calculation)

**Response**:
```json
{
  "status": "success",
  "data": {
    "id": "uuid",
    "title": "Fitness First Gym",
    "category": {
      "id": 1, "name": "Gym", "icon": "bi-bicycle",
      "has_shifts": true, "has_trainers": true,
      "has_rooms": false, "has_attendance": true
    },
    "about": "Best gym in town.",
    "address": "MG Road, Bangalore",
    "landmark": "Near Metro Station",
    "opening_time": "06:00",
    "closing_time": "22:00",
    "phone": "9000000000",
    "lat": 12.9716, "lng": 77.5946,
    "distance_km": 1.23,
    "starting_price": 799,
    "rating": {
      "average": 4.3,
      "count": 38,
      "distribution": {
        "5": { "count": 20, "percent": 52.6 },
        "4": { "count": 10, "percent": 26.3 },
        "3": { "count": 5,  "percent": 13.2 },
        "2": { "count": 2,  "percent": 5.3 },
        "1": { "count": 1,  "percent": 2.6 }
      }
    },
    "images": [{ "id": 1, "url": "https://..." }],
    "partner": { "name": "Partner Name", "mobile": "9111111111", "profile_photo_url": "url" }
  }
}
```

---

### Facilities
`GET /listings/{id}/facilities`

Returns dynamic custom fields (e.g., AC/Non-AC, Equipment Type).

---

### Staff / Trainers
`GET /listings/{id}/staff`

Returns the list of trainers or staff for the listing.

---

### Single Staff Profile
`GET /listings/{id}/staff/{staffId}`

Returns detailed profile for one trainer/staff member.

---

### Branches
`GET /listings/{id}/branches`

Returns other listings by the same partner under the same category.

---

### Reviews
`GET /listings/{id}/reviews`

Returns paginated customer reviews + average rating.

---

### Plan Durations
`GET /listings/{id}/durations`

Returns available plan types (monthly, quarterly, half_yearly, yearly, custom).

---

### Plans (Packages + Shifts + Rooms)
`GET /listings/{id}/plans`

Returns packages, batch/shift times, and available rooms.

**Query Params**: 
- `?type=monthly` (filter by duration type)
- `?floor_id=1` (filter available rooms and plans by a specific floor)
- `?room_id=uuid` (filter available rooms and plans by a specific room)

**Response**:
```json
{
  "status": "success",
  "data": {
    "plans": [
      { 
        "id": "uuid", "name": "Monthly Basic", "type": "monthly",
        "duration_label": "Monthly", "duration_days": 30, "price": 999, 
        "features": [], "room_type": null, "room_id": null, "occupancy_type": "standard", "meal_plans": null
      }
    ],
    "rooms": [
      { 
        "id": "uuid", "floor_id": 1, "room_number": "101", "room_type": "1 BHK", 
        "capacity": 2, "available_beds": 2, "security_deposit": 5000,
        "packages": [
          { "id": "uuid", "name": "Monthly Basic", "type": "monthly", "duration_days": 30, "price": 999, "features": [] }
        ]
      }
    ],
    "batch_times": [
      { "id": 1, "name": "morning", "start_time": "06:00", "end_time": "09:00", "max_members": 20, "fee": 0 }
    ],
    "has_shifts": true,
    "has_rooms": true
  }
}
```

---

### Floors
`GET /listings/{id}/floors`

Returns all floors available for the listing (empty array if no rooms).

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "floor_number": 1,
      "name": "Ground Floor"
    }
  ]
}
```

---

### Rooms
`GET /listings/{id}/rooms`

Returns all rooms for the listing.

**Query Params**: 
- `?floor_id=1` (filter by floor)
- `?room_id=uuid` (filter by specific room)

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id": "uuid",
      "floor_id": 1,
      "room_number": "101",
      "room_type": "1 BHK",
      "capacity": 2,
      "available_beds": 2,
      "security_deposit": 5000,
      "images": [
        { "id": 1, "url": "https://example.com/img.jpg" }
      ]
    }
  ]
}
```

---

### Room Details
`GET /listings/{id}/rooms/{roomId}`

Returns full details for a specific room, including its images and all applicable packages.

**Response**:
```json
{
  "status": "success",
  "data": {
    "id": "uuid",
    "floor_id": 1,
    "room_number": "101",
    "room_type": "1 BHK",
    "capacity": 2,
    "available_beds": 2,
    "security_deposit": 5000,
    "images": [
      { "id": 1, "url": "https://example.com/img.jpg" }
    ],
    "packages": [
      { 
        "id": "uuid", "name": "Monthly Basic", "type": "monthly",
        "duration_label": "Monthly", "duration_days": 30, "price": 999, 
        "features": [], "room_type": null, "room_id": null, "occupancy_type": "standard", "meal_plans": null
      }
    ],
    "listing": {
      "id": "uuid",
      "title": "Star PG",
      "about": "A nice place to stay.",
      "address": "123 Main St",
      "landmark": "Near Station",
      "phone": "9876543210",
      "lat": "28.12345",
      "lng": "77.12345",
      "distance_km": null,
      "rating": {
        "average": 4.5,
        "count": 10
      },
      "category": {
        "id": 1,
        "name": "PG"
      }
    },
    "partner": {
      "name": "John Doe",
      "mobile": "9988776655",
      "profile_photo_url": "https://example.com/images/default.png"
    }
  }
}
```

---

## 4. Coupons & Billing Preview (Public)

### List Available Coupons
`GET /coupons`

Returns all active and valid coupons.

---

### Apply Coupon
`POST /coupons/apply`

**Body**: `{ "code": "WELCOME50", "package_id": "uuid" }`

**Response**: Discount amount and billing preview.

---

### Billing Preview
`GET /bookings/billing-preview`

Returns exact billing breakdown before booking.

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `package_id` | uuid (required) | The selected plan |
| `coupon_code` | string (optional) | Coupon to apply |
| `shift_id` | uuid (optional) | Include shift fee |
| `beds_booked` | integer (optional) | For per-bed priced rooms |
| `room_id` | uuid (optional) | Selected room |

**Validation Rules**:
- If `occupancy_type` is `full_room`, booking is blocked with a 422 error if any bed is already occupied. If successful, `beds_booked` is automatically set to the room's total capacity.
- If `occupancy_type` is `per_bed`, `beds_booked` cannot exceed the room's `available_beds` (returns 422 error if exceeded).
- For `per_bed` bookings, the `security_deposit` is automatically multiplied by `beds_booked`.
- For `per_site` (e.g., gym memberships), `beds_booked` represents the number of members/seats booked. The base package price is automatically multiplied by `beds_booked`.
- If the listing's category `has_shifts` is true and a `shift_id` is passed, the system checks if the shift's `max_members` limit has been reached, subtracting `beds_booked` (representing seats booked). Returns 422 error if fully booked.

**Response**:
```json
{
  "status": "success",
  "data": {
    "plan_name": "Monthly Basic",
    "duration": "Monthly",
    "duration_days": 30,
    "subtotal": 999,
    "tax": 0,
    "discount": 100,
    "security_deposit": 9500,
    "total": 10399,
    "room": {
      "room_number": "202",
      "room_type": "single",
      "security_deposit": 9500
    },
    "shift": {
      "id": "uuid",
      "shift_name": "morning",
      "start_time": "06:00",
      "end_time": "09:00",
      "fee": 0
    },
    "trainers": [
      {
        "id": "uuid",
        "name": "John Doe",
        "specialization": "Weightlifting",
        "photo": "https://example.com/photo.jpg"
      }
    ],
    "shift_fee": 0,
    "trainer_fee": 0,
    "beds_booked": 1
  }
}
```

---

## 5. Visit Requests (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Create Visit Request
`POST /visits`

Books a site visit for the customer. Partner can accept/reject via web dashboard.

**Body**:
```json
{
  "listing_id": "uuid",
  "visit_date": "2026-07-01",
  "visit_time": "10:00",
  "note": "Looking for gym membership"
}
```

**Response** `201 Created`:
```json
{
  "status": "success",
  "data": { "id": "uuid", "status": "pending", "visit_date": "2026-07-01", ... }
}
```

---

### List My Visits
`GET /visits`

Returns paginated list of the customer's visit requests.

---

### Visit Details
`GET /visits/{id}`

Returns a specific visit including listing images with full absolute URLs (`url`). Accessible by the customer, partner, or admin.

---

### Update Visit Details
`PUT /visits/{id}`

Updates the visit date, time, or note. Only allowed when status is `pending` or `accepted`.

**Body**:
```json
{
  "visit_date": "2026-07-05",
  "visit_time": "12:00",
  "note": "Updated note"
}
```
**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Visit updated successfully",
  "data": { "id": "uuid", "status": "pending", "visit_date": "2026-07-05", ... }
}
```

---

### Cancel Visit
`DELETE /visits/{id}`

Cancels a visit. Only allowed when status is `pending`.

---

## 6. Booking & Payment Flow (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Create Booking
`POST /bookings`

Creates a booking in `pending_otp` state. An invoice is also auto-created.

**Body**:
```json
{
  "package_id":     "uuid",
  "payment_method": "cash | online | auto_pay",
  "coupon_code":    "SAVE20",
  "shift_id":       "optional-uuid",
  "beds_booked":    1,
  "room_id":        "optional-uuid",
  "trainer_ids":    ["trainer-uuid-1"]
}
```

**Validation Rules**:
- If `occupancy_type` is `full_room`, booking is blocked with a 422 error if any bed is already occupied (`available_beds < capacity`). If successful, `beds_booked` is automatically set to the room's total capacity.
- If `occupancy_type` is `per_bed`, `beds_booked` cannot exceed the room's `available_beds` (returns 422 error if exceeded).
- For `per_bed` bookings, the `security_deposit` is automatically multiplied by `beds_booked`.
- For `per_site` (e.g., gym memberships), `beds_booked` represents the number of members/seats booked. The base package price is automatically multiplied by `beds_booked`.
- If the listing's category `has_shifts` is true and a `shift_id` is passed, the system checks if the shift's `max_members` limit has been reached, subtracting `beds_booked` (representing seats booked). Returns 422 error if fully booked.

**Response** `201 Created`:
```json
{
  "status": "success",
  "message": "Booking created. Waiting for partner OTP confirmation.",
  "data": {
    "booking_id":      "uuid",
    "status":          "pending_otp",
    "otp":             "123456",
    "payment_method":  "cash",
    "billing":         { "subtotal": 999, "security_deposit": 9500, "total": 10399, "discount": 100, "shift_fee": 0, "trainer_fee": 0, "beds_booked": 1 },
    "invoice_id":      "uuid",
    "invoice_number":  "INV-XXXXXXXX"
  }
}
```

---

### My Bookings
`GET /bookings`

Returns paginated list of customer's bookings (all statuses).

---

### Booking Detail
`GET /bookings/{id}`

Returns full booking detail including invoice and payment history.

---

### Verify OTP (Customer Side)
`POST /bookings/{id}/verify-otp`

**Body**: `{ "otp": "123456" }`

---

### Resend Booking OTP
`POST /bookings/{id}/resend-otp`

Resends the OTP to the customer's mobile.

---

### Cancel Booking
`POST /bookings/{id}/cancel`

Cancels the booking. The booking must not be in `completed` or `cancelled` state. Associated unpaid invoices will also be cancelled.

**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Booking has been cancelled successfully.",
  "data": {
    "booking_id": "uuid",
    "status": "cancelled"
  }
}
```

---

### Pay for Booking
`POST /bookings/{id}/pay`

Booking must be in `confirmed` status (OTP verified) before paying.

**Body**: `{ "method": "cash | online | auto_pay" }`

| Method | Behaviour |
|---|---|
| `cash` | Immediately marks subscription `active`, payment recorded as `paid` |
| `online` | Returns Razorpay `order_id` — client completes payment then calls `POST /payments/verify` |
| `auto_pay` | Sets up Razorpay recurring subscription — call `POST /payments/autopay/verify` after |

**Response (online)**:
```json
{
  "status": "success",
  "data": {
    "razorpay_order_id": "order_XXXXXXXX",
    "amount": 89900,
    "currency": "INR",
    "booking_id": "uuid"
  }
}
```

---

### Verify Online Payment
`POST /payments/verify`

**Body**:
```json
{
  "invoice_id":  "uuid",
  "gateway":     "razorpay",
  "gateway_ref": "pay_XXXXXXXX"
}
```

---

### Verify Autopay
`POST /payments/autopay/verify`

**Body**:
```json
{
  "subscription_id":         "uuid",
  "gateway_subscription_id": "sub_XXXXXXXX",
  "razorpay_payment_id":     "pay_XXXXXXXX",
  "razorpay_signature":      "signature_hash"
}
```

---

### Payment History
`GET /payments/history`

Returns all past payments for the logged-in customer.

---

### Submit Review
`POST /listings/{id}/reviews`

**Body**: `{ "rating": 5, "comment": "Great experience!" }`

---

### My Subscriptions
`GET /subscriptions`

Returns paginated list of the customer's active and historical subscriptions (services).

---

### Subscription Detail
`GET /subscriptions/{id}`

Returns full subscription detail including linked booking, invoices, and payment history.

---

## 7. Attendance — Punch In / Out (Auth Required)

> All endpoints require `Authorization: Bearer <token>`
>
> **Prerequisite**: The listing's category must have `has_attendance: true`.
> The customer must have an **active subscription** at the listing.

### Punch In
`POST /attendance/punch-in`

Records the customer's entry for today. Only one punch-in is allowed per subscription per day.

**Body**:
```json
{ "listing_id": "uuid" }
```

**Response** `201 Created`:
```json
{
  "status": "success",
  "message": "Punched in successfully.",
  "data": {
    "id": 1,
    "date": "2026-06-22",
    "punch_in_at": "07:02:45",
    "punch_out_at": null,
    "duration_minutes": null,
    "status": "open",
    "listing": "Fitness First Gym",
    "listing_id": "uuid"
  }
}
```

**Error cases**:
| Status | Message |
|---|---|
| `403` | `No active subscription found for this listing.` |
| `403` | `Attendance is not enabled for this category.` |
| `422` | `You have already punched in today. Please punch out first.` |
| `422` | `Attendance already completed for today.` |

---

### Punch Out
`POST /attendance/punch-out`

Records the customer's exit. Auto-calculates `duration_minutes`.

**Body**:
```json
{ "listing_id": "uuid" }
```

**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Punched out successfully.",
  "data": {
    "id": 1,
    "date": "2026-06-22",
    "punch_in_at": "07:02:45",
    "punch_out_at": "09:15:00",
    "duration_minutes": 132,
    "status": "completed",
    "listing": "Fitness First Gym",
    "listing_id": "uuid"
  }
}
```

**Error cases**:
| Status | Message |
|---|---|
| `422` | `You have not punched in today.` |
| `422` | `You have already punched out today.` |

---

### Today's Attendance Status
`GET /attendance/today`

Returns the customer's punch-in record(s) for today.

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `listing_id` | uuid (optional) | Filter by a specific listing |

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "date": "2026-06-22",
      "punch_in_at": "07:02:45",
      "punch_out_at": null,
      "duration_minutes": null,
      "status": "open",
      "listing": "Fitness First Gym",
      "listing_id": "uuid"
    }
  ]
}
```
> `status` values: `open` (punched in, not yet out) | `completed` (punched out) | `absent` (no record)

---

### Attendance History
`GET /attendance`

Returns the customer's full paginated attendance history.

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `listing_id` | uuid (optional) | Filter by listing |
| `from` | date (optional) | Filter from date `YYYY-MM-DD` |
| `to` | date (optional) | Filter to date `YYYY-MM-DD` |

**Response** (paginated):
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 10,
        "date": "2026-06-21",
        "punch_in_at": "06:55:00",
        "punch_out_at": "08:45:00",
        "duration_minutes": 110,
        "status": "completed",
        "listing": "Fitness First Gym",
        "listing_id": "uuid"
      }
    ]
  }
}
```

---

### Partner: View Attendance Logs
`GET /partner/attendance`

Returns attendance logs for the partner's listings (paginated, 30 per page).

**Query Params**:
| Param | Type | Description |
|---|---|---|
| `listing_id` | uuid (optional) | Filter by a specific listing |
| `date` | date (optional) | Exact date filter `YYYY-MM-DD` |
| `from` | date (optional) | From date |
| `to` | date (optional) | To date |

**Response**:
```json
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "date": "2026-06-22",
        "punch_in_at": "07:02:45",
        "punch_out_at": "09:15:00",
        "duration_minutes": 132,
        "status": "completed",
        "customer": { "id": "uuid", "name": "John Doe", "mobile": "9000000000" },
        "listing": "Fitness First Gym",
        "plan": "Monthly Basic"
      }
    ]
  }
}
```

---

## 8. Customer KYC Profile (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Get KYC Profile
`GET /kyc`

Returns the logged-in customer's current KYC profile data, including document URLs.

**Response**:
```json
{
  "status": "success",
  "data": {
    "status": "pending",
    "rejection_reason": null,
    "live_photo": "https://...",
    "aadhaar_front": "https://...",
    "bank_statement": "https://..."
  }
}
```

---

### Submit KYC Profile
`POST /kyc`

Submit or update KYC details. 
- You can upload files as `multipart/form-data`.
- For `live_photo`, you can send a file (`live_photo_file`) OR a base64 string (`live_photo`).

**Content-Type**: `multipart/form-data` (or JSON if using base64 for live photo without other files, but forms are recommended)

**Body**:
- `live_photo`: string (base64) OR `live_photo_file`: file (image)
- `aadhaar_front`: file (image/pdf)
- `aadhaar_back`: file (image/pdf)
- `pan_front`: file (image/pdf)
- `pan_back`: file (image/pdf)
- `bank_statement`: file (image/pdf)
- `passport_front`: file (image/pdf) - Optional
- `passport_back`: file (image/pdf) - Optional
- `driving_license_front`: file (image/pdf) - Optional
- `driving_license_back`: file (image/pdf) - Optional

**Response**:
```json
{
  "status": "success",
  "message": "KYC details submitted successfully and are pending review."
}
```

---

## 9. Account Management (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Get Profile
`GET /profile`

Returns the current customer's profile.

---

### Update Profile
`POST /profile` (or `PUT /profile`)

Updates the user's profile information. Use `POST` with `multipart/form-data` if you are uploading a profile image.

**Content-Type**: `multipart/form-data` or `application/json`

**Body**: 
- `name`: "Jane Doe"
- `email`: "jane@example.com"
- `profile_image`: (File upload - optional)

---

### Update FCM Token
`PUT /fcm-token`

Updates the Firebase push notification token.

**Body**: `{ "fcm_token": "firebase-token-string" }`

---

### Logout
`POST /logout`

Invalidates the current JWT token.

**Response**: `{ "status": "success", "message": "Logged out successfully" }`

---

## 10. Dashboard (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Recent Bookings
`GET /dashboard/recent-bookings`

Returns the logged-in customer's 5 most recent bookings.

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "booking_id": "uuid",
      "status": "completed",
      "plan": { ... },
      "listing": { ... }
    }
  ]
}
```

---

### Recent Transactions
`GET /dashboard/recent-transactions`

Returns the logged-in customer's 5 most recent transactions (payments).

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id": 12,
      "gateway": "razorpay",
      "amount": 899,
      "status": "paid",
      "listing_name": "Fitness First Gym"
    }
  ]
}
```

---

## 11. Subscriptions (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### List My Subscriptions
`GET /subscriptions`

Returns paginated list of the customer's active, completed, and cancelled subscriptions.

### Subscription Details
`GET /subscriptions/{id}`

Returns a specific subscription.

### Cancellation Request History
`GET /subscriptions/cancellations/history`

Returns a paginated list of all cancellation requests made by the customer, including their current `leave_status` (requested, approved, or rejected).

**Response**:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": "uuid",
        "status": "active",
        "leave_status": "requested",
        "package": {
          "name": "Monthly Basic"
        }
      }
    ]
  }
}
```

### Request Cancellation (Leave Request)
`POST /subscriptions/{id}/request-cancellation`

Submit a request to cancel an active subscription (useful for PGs where notice period is required, or requesting security deposit refund).

**Body**:
```json
{
  "reason": "Moving to another city",
  "leave_date": "2026-08-01"
}
```

**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Cancellation request submitted successfully. Awaiting partner approval."
}
```

---

## 12. Wallet (Auth Required)

> All endpoints require `Authorization: Bearer <token>`

### Get Wallet Summary
`GET /wallet/summary`

Returns the customer's wallet balance, total credit, and total debit.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "wallet_balance": 1000.0,
    "total_credit": 1500.0,
    "total_debit": 500.0
  }
}
```

---

### Get Reserve History
`GET /wallet/reserve-history`

Returns paginated list of security deposits currently held or refunded, including the breakdown of the booking total, rent, and deposit.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1,
    "history": [
      {
        "id": 1,
        "amount": 7117.00,
        "status": "active",
        "created_at": "2026-07-22T10:00:00.000000Z",
        "booking_id": "uuid",
        "partner": {
          "name": "Partner User",
          "mobile": "9876543210"
        },
        "listing": {
          "id": 2,
          "title": "AJ VAI PG Boys and Girls"
        },
        "breakdown": {
          "total": 7117.00,
          "rent": 120.00,
          "deposit": 6997.00
        }
      }
    ]
  }
}
```

---

## 12. Wallet (Auth Required)

### Get Reserve History
`GET /wallet/reserve-history`

Returns the security deposits paid by the customer for active bookings.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "last_page": 1,
    "total": 1,
    "history": [
      {
        "id": 10,
        "amount": 5000,
        "status": "active",
        "created_at": "2024-11-20T10:00:00.000000Z",
        "partner": {
          "name": "Acme Partner",
          "mobile": "9876543210"
        },
        "listing": {
          "id": 5,
          "title": "Deluxe PG"
        }
      }
    ]
  }
}
```

---

### Get Wallet History
`GET /wallet/history`

Returns the ledger of wallet transactions (credits and debits) for the customer.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "amount": "1000.00",
        "type": "credit",
        "description": "Wallet Recharge Approved",
        "created_at": "2024-11-21T10:00:00.000000Z"
      }
    ]
  }
}
```

---

### Get Recharge Configuration & QR Code
`GET /wallet/recharge-config?amount=1000`

Returns the Admin's configured UPI ID and an automatically generated QR Code URL. If `amount` is passed, the QR code is generated for that specific amount.

**Query Parameters:**
- `amount` (optional): The amount in INR to embed in the QR code.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "admin_upi_id": "admin@upi",
    "qr_code_url": "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi%3A%2F%2Fpay%3Fpa%3Dadmin%40upi%26pn%3DFeetrack%26am%3D1000%26cu%3DINR"
  }
}
```

---

### Request Wallet Recharge
`POST /wallet/recharge/request`

Submits a manual wallet recharge request using a transaction ID (UTR number). 

**Body**:
```json
{
  "amount": 1000,
  "utr_number": "UPI1234567890"
}
```

**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Recharge request submitted successfully. Waiting for admin approval.",
  "data": {
    "id": 5,
    "user_id": 12,
    "amount": 1000,
    "transaction_id": "UPI1234567890",
    "status": "pending"
  }
}
```

---

### Request Wallet Withdrawal
`POST /wallet/withdraw`

Submits a withdrawal request to transfer wallet funds to the customer's bank/UPI.

**Body**:
```json
{
  "amount": 500,
  "payment_method": "UPI: customer@upi",
  "notes": "Refund please"
}
```

**Response** `200 OK`:
```json
{
  "status": "success",
  "message": "Withdrawal request submitted successfully."
}
```

---

### Get Wallet Recharge Requests History
`GET /wallet/recharges`

Returns the history of wallet recharge requests made by the customer, including their approval status.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "amount": "1000.00",
        "transaction_id": "UPI1234567890",
        "notes": null,
        "status": "approved",
        "approved_at": "2024-11-21T10:00:00.000000Z",
        "created_at": "2024-11-21T09:00:00.000000Z"
      }
    ]
  }
}
```

---

### Get Wallet Withdrawal Requests History
`GET /wallet/withdrawals`

Returns the history of wallet withdrawal requests made by the customer, including their payout status.

**Response** `200 OK`:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "amount": "500.00",
        "payment_method": "UPI: user@upi",
        "notes": "Refund",
        "status": "approved",
        "paid_at": "2024-11-21T10:00:00.000000Z",
        "created_at": "2024-11-21T09:00:00.000000Z"
      }
    ]
  }
}
```

---

## Status Reference

### Booking Status Flow
```
pending_otp → confirmed → completed
```
| Status | Meaning |
|---|---|
| `pending_otp` | Booking created, waiting for OTP confirmation |
| `confirmed` | OTP verified, ready for payment |
| `completed` | Payment done, subscription created and active |
| `cancelled` | Booking cancelled before payment |

### Subscription Status Flow
```
active → expired / cancelled
```
| Status | Meaning |
|---|---|
| `active` | Service is currently ongoing |
| `cancelled` | Service cancelled by user/admin |
| `expired` | Plan duration has elapsed |

### Attendance Status Values
| Status | Meaning |
|---|---|
| `open` | Punched in, not yet punched out |
| `completed` | Punched in and out |
| `absent` | No record for that day |

---

## Error Response Format

All errors follow this structure:
```json
{
  "status": "error",
  "message": "Human-readable error description"
}
```

Common HTTP codes:
| Code | Meaning |
|---|---|
| `200` | Success |
| `201` | Resource created |
| `401` | Unauthenticated / invalid OTP |
| `403` | Forbidden (wrong role, feature disabled). **Note:** For Bookings and Visits, if KYC is not approved, returns `403` with `error_code: "KYC_REQUIRED"`. |
| `404` | Resource not found |
| `422` | Validation error or business rule violation |
| `429` | Too many requests (e.g., OTP resend cooldown) |
| `500` | Internal server error |
