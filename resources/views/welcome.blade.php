<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Code Test - Laravel REST API</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: #fff;
      font-family: "Poppins", sans-serif;
      overflow-x: hidden;
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .header-title {
      font-size: 2.2rem;
      font-weight: bold;
      letter-spacing: 1px;
    }
    .btn-glow {
      transition: all 0.3s ease;
    }
    .btn-glow:hover {
      box-shadow: 0 0 20px rgba(255,255,255,0.6);
      transform: scale(1.05);
    }
    .case-box {
      background: rgba(255,255,255,0.1);
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    footer {
      font-size: 0.9rem;
      opacity: 0.85;
    }
  </style>
</head>
<body>

  <div class="container py-5 text-center">
    <h1 class="header-title animate__animated animate__fadeInDown"><i class="fa-solid fa-code"></i> PHP Code Test</h1>
    <p class="lead animate__animated animate__fadeInUp">Simple REST API using Laravel 10 — Test Instructions</p>

    <div class="row justify-content-center mt-4">
      <div class="col-md-8">
        <div class="card text-dark p-4 animate__animated animate__fadeInUp">
          <div class="card-body">
            <h4 class="card-title mb-3"><i class="fa-solid fa-user-plus text-primary"></i> Create User API (POST /api/users)</h4>
            <p>Use this endpoint to create a new user in the database. Two emails will be sent: one to the new user and one to the administrator.</p>
            <pre class="text-bg-dark text-start p-3 rounded">
POST /api/users
{
  "email": "example@example.com",
  "password": "password123",
  "name": "John Doe"
}
→ Returns: { "id": 123, "email": "example@example.com", "name": "John Doe", "created_at": "..." }
            </pre>

            <h4 class="card-title mt-4"><i class="fa-solid fa-users text-success"></i> Get Users API (GET /api/users)</h4>
            <p>Use this to retrieve a paginated list of active users. Supports searching and sorting.</p>
            <pre class="text-bg-dark text-start p-3 rounded">
GET /api/users?search=john&page=1&sortBy=name
→ Returns paginated list with fields:
id, email, name, role, created_at, orders_count, can_edit
            </pre>

            <hr>
            <h5 class="mt-3"><i class="fa-solid fa-flask text-warning"></i> Example Test Cases</h5>
            <div class="case-box text-start">
              <b>Case 1:</b> Create user with invalid email → expect 422 validation error.
            </div>
            <div class="case-box text-start">
              <b>Case 2:</b> Create valid user → expect JSON response with ID and timestamps, and email sent.
            </div>
            <div class="case-box text-start">
              <b>Case 3:</b> Get users list with search=“john” → expect matched users with orders_count.
            </div>

            <button id="showTip" class="btn btn-lg btn-primary mt-3 btn-glow">
              <i class="fa-solid fa-lightbulb"></i> Show Fun Tip
            </button>
          </div>
        </div>
      </div>
    </div>

    <footer class="mt-5 animate__animated animate__fadeInUp">
      <i class="fa-solid fa-mug-hot"></i> Supaya Bisa Running Coba Aja! 💻☕  
      <br><a href="https://github.com/gumuruhsspj/php-code-test"><span class="text-warning">Github</span> </a>
    </footer>
  </div>

  <script>
    $(document).ready(function() {
      $("#showTip").click(function() {
        const tips = [
          "Coding is 10% typing and 90% debugging 😅",
          "Remember: with great power comes great StackOverflow tabs 🧠",
          "API stands for Always Pending Integration 😜",
          "Laravel devs don’t sleep — they just migrate 💤"
        ];
        const randomTip = tips[Math.floor(Math.random() * tips.length)];
        const tipBox = $(`<div class='alert alert-info mt-4 animate__animated animate__bounceIn'>
          <i class='fa-solid fa-face-grin-beam'></i> ${randomTip}
        </div>`);
        $(".card-body").append(tipBox);
        setTimeout(() => tipBox.fadeOut(1500, () => tipBox.remove()), 4000);
      });
    });
  </script>
</body>
</html>
