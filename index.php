<?php 
// --------------------------------------------------------------
// AGRINOVA - HOME PAGE
// No session checks here (visible to all).
// Highlights main features + weather search.
// --------------------------------------------------------------

session_start();

// Store user info if logged in
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

// Include shared components
$activePage = 'home';       // For navbar active state
include "includes/navbar.php";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<style>
    /* Simple greenish theme */
    .hero-section {
        background: linear-gradient(rgba(0, 80, 0, 0.6), rgba(0, 80, 0, 0.6)), 
                    url('assets/hero.jpg') center/cover no-repeat;
        padding: 120px 0;
        color: #fff;
        text-align: center;
    }
    .feature-card {
        border-radius: 12px;
        transition: 0.3s;
        border: none;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 4px 20px rgba(0,0,0,0.2);
    }
    .weather-box {
        border-radius: 10px;
        padding: 20px;
        background: #f2fff5;
    }
    /* Fixed navbar */
    nav.navbar { position: fixed; top: 0; width: 100%; z-index: 1030; }
</style>


<!-- HERO SECTION -->
<div class="hero-section">
    <div class="container">
        <h1 class="fw-bold display-4">Welcome to AgriNova</h1>
        <p class="lead mt-3 mb-4">
            Smart Agriculture Platform with AI-Powered Plant Disease Detection.
        </p>
        <a href="prediction.php" class="btn btn-light btn-lg fw-bold px-4">Try Prediction</a>
    </div>
</div>


<!-- MAIN FEATURES SECTION -->
<div class="container my-5">

    <h2 class="text-center fw-bold mb-4 text-success">Our Main Features</h2>

    <div class="row g-4">

        <!-- Feature 1 -->
        <div class="col-md-4">
            <div class="card feature-card p-3">
                <h4 class="fw-bold text-success">AI Plant Disease Prediction</h4>
                <p class="small">
                    Upload potato or tomato leaf images and get instant results powered by our CNN Model.
                </p>
                <a href="prediction.php" class="btn btn-success btn-sm">Start Prediction</a>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-4">
            <div class="card feature-card p-3">
                <h4 class="fw-bold text-success">Knowledge Hub</h4>
                <p class="small">
                    Read blogs, explore crop details, remedies, guides, and modern farming techniques.
                </p>
                <a href="blogs.php" class="btn btn-success btn-sm">Explore</a>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-4">
            <div class="card feature-card p-3">
                <h4 class="fw-bold text-success">Agri Officer Portal</h4>
                <p class="small">
                    Farmers can request field visits, ask questions, and view announcements.
                </p>
                <a href="agri_officer_portal.php" class="btn btn-success btn-sm">View Portal</a>
            </div>
        </div>

    </div>
</div>



<!-- WEATHER SECTION -->
<div class="container mb-5">
    <h2 class="text-center fw-bold mb-4 text-success">Check Weather</h2>

    <div class="weather-box shadow-sm">

        <form id="weatherForm" class="row g-3">
            <div class="col-md-9">
                <input type="text" id="weatherCity" class="form-control" placeholder="Enter city (e.g., Colombo)" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success w-100">Get Weather</button>
            </div>
        </form>

        <div id="weatherResult" class="mt-4"></div>

    </div>
</div>



<script>
// -----------------------------------------------
// WEATHER SEARCH (OpenWeatherMap API)
// -----------------------------------------------
document.getElementById("weatherForm").addEventListener("submit", function(e){
    e.preventDefault();

    const city = document.getElementById("weatherCity").value.trim();
    const apiKey = "ae71af3fde6968bc95a3f6c21a44a702";  //API key

    if(city === "") return;

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            if(data.cod !== 200){
                document.getElementById("weatherResult").innerHTML =
                    `<p class="text-danger">City not found. Try again.</p>`;
                return;
            }

            document.getElementById("weatherResult").innerHTML = `
                <div class="p-3 bg-white rounded shadow-sm">
                    <h5 class="text-success fw-bold">${data.name}, ${data.sys.country}</h5>
                    <p class="mb-1">Temperature: <strong>${data.main.temp}°C</strong></p>
                    <p class="mb-1">Condition: <strong>${data.weather[0].description}</strong></p>
                    <p class="mb-1">Humidity: <strong>${data.main.humidity}%</strong></p>
                    <p class="mb-0">Wind: <strong>${data.wind.speed} m/s</strong></p>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById("weatherResult").innerHTML =
                `<p class="text-danger">Unable to fetch weather data.</p>`;
        });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<?php include "includes/footer.php"; ?>
