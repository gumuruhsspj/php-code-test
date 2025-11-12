

# Simple REST API - Laravel 10

## 📘 Petunjuk Pemakaian

Proyek ini merupakan simple REST API yang dibangun menggunakan Laravel 10.  
Terdapat dua endpoint utama:

---

## 🚀 API Endpoints

### 1️⃣ Create User API  
Endpoint:  
```

POST /api/users

````

Request Input:
| Field | Type | Required | Description |
|--------|------|-----------|--------------|
| email | string | ✅ | Valid email format |
| password | string | ✅ | Minimum 8 characters |
| name | string | ✅ | 3–50 characters |

Functionality:
- Menyimpan data user baru ke tabel `users`.
- Mengirim dua email:
  1. Kepada user baru sebagai konfirmasi akun.
  2. Kepada administrator sistem untuk pemberitahuan user baru.
- Mengembalikan response berisi detail user yang baru dibuat (tanpa password).

Response Example:
```json
{
  "id": 123,
  "email": "example@example.com",
  "name": "John Doe",
  "created_at": "2024-11-25T12:34:56Z"
}
````

---

### 2️⃣ Get Users API

Endpoint:

```
GET /api/users
```

Request Input (optional):

| Parameter | Type    | Description                                    |
| --------- | ------- | ---------------------------------------------- |
| search    | string  | Filter berdasarkan nama atau email             |
| page      | integer | Default: 1                                     |
| sortBy    | string  | Possible values: `name`, `email`, `created_at` |

Functionality:

* Mengambil daftar user aktif dari tabel `users` (paginated).
* Dapat difilter menggunakan `search` (nama/email).
* Dapat disortir berdasarkan `sortBy` (default: `created_at`).
* Mengabaikan field `password` dalam response.
* Menambahkan field tambahan:

  * `orders_count`: total jumlah pesanan (`orders`) per user.
  * `can_edit`: status apakah user yang sedang login dapat mengedit user tersebut.

#### 🔒 Rules untuk `can_edit`:

| Role          | Hak Edit                                     |
| ------------- | -------------------------------------------- |
| Administrator | Dapat mengedit semua user                    |
| Manager       | Hanya dapat mengedit user dengan role `user` |
| User          | Hanya dapat mengedit dirinya sendiri         |

Response Example:

```json
{
  "page": 1,
  "users": [
    {
      "id": 123,
      "email": "example@example.com",
      "name": "John Doe",
      "role": "user",
      "created_at": "2024-11-25T12:34:56Z",
      "orders_count": 10,
      "can_edit": true
    },
    {
      "id": 124,
      "email": "another@example.com",
      "name": "Jane Smith",
      "role": "manager",
      "created_at": "2024-11-24T11:20:30Z",
      "orders_count": 5,
      "can_edit": false
    }
  ]
}
```

---

## 🧩 Database Structure

### 🧑‍💻 users Table

| Column     | Type         | Constraints                 |
| ---------- | ------------ | --------------------------- |
| id         | INT          | Primary Key, Auto Increment |
| email      | VARCHAR(255) | Unique, Not Null            |
| password   | VARCHAR(255) | Not Null                    |
| name       | VARCHAR(255) | Not Null                    |
| role       | ENUM         | Default: `user`             |
| active     | BOOLEAN      | Default: `true`             |
| created_at | DATETIME     | Default: Current Timestamp  |

---

### 📦 orders Table

| Column     | Type     | Constraints                 |
| ---------- | -------- | --------------------------- |
| id         | INT      | Primary Key, Auto Increment |
| user_id    | INT      | Foreign Key → users.id      |
| created_at | DATETIME | Default: Current Timestamp  |

---

## 🧠 Test Description & Requirements

Objective:
Membuat dua endpoint REST API yang berfungsi penuh:

1. Create User API
2. Get Users API

Kriteria:

* Menggunakan Laravel 10.
* Respons JSON valid.
* Implementasi `Eloquent ORM` untuk relasi dan pagination.
* Tidak menampilkan password dalam response.
* Email dikirim menggunakan Laravel Mail (simulasi aja).



## 📬 Testing API (Contoh dengan Postman)

1. POST /api/users

   * Body → `raw JSON`

   ```json
   {
     "email": "user@example.com",
     "password": "password123",
     "name": "John Doe"
   }
   ```

2. GET /api/users

   * Params (opsional):
     `search`, `page`, `sortBy`


---

## 📖 Developer Notes

For additional explanation, workflow, and guidance for this project, please refer to the Developer Notes PDF:  
[Developer Notes (PDF)](public/dev_notes/developer_notes.pdf)

---