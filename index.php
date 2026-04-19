<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weather Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<!-- Topbar -->
<div class="topbar">
    <h3>Welcome <?php echo $_SESSION['user']; ?> 👋</h3>

    <div>
        <button onclick="toggleTheme()">🌙</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</div>

<!-- Main Container -->
<div class="container">

    <h1>🌦 Weather Dashboard</h1>

    <!-- Search + Location + Voice -->
    <div class="search-box">
        <input id="cityInput" placeholder="Enter city">
        <button onclick="getWeather()">Search</button>
        <button onclick="getLocationWeather()">📍</button>
        <button onclick="startVoice()">🎤</button>
    </div>

    <!-- Favorites -->
    <h3>⭐ Favorites</h3>
    <div id="favorites"></div>

    <!-- Weather Card -->
    <div class="card" id="weatherCard" style="display:none;">

        <h2 id="cityName"></h2>
        <img id="icon">
        <h2 id="temp"></h2>
        <p id="condition"></p>
        <p id="suggestion"></p>

        <!-- Save Favorite -->
        <button onclick="saveFavorite()">⭐ Save</button>

        <!-- Extra Data -->
        <div class="extra">
            <span id="humidity"></span>
            <span id="wind"></span>
            <span id="feels"></span>
            <span id="pressure"></span>
        </div>

    </div>

    <!-- Forecast -->
    <h3>⏳ Forecast</h3>
    <div id="forecast"></div>

    <!-- Chart -->
    <h3>📊 Temperature Chart</h3>
    <canvas id="chart"></canvas>

</div>

<!-- JS -->
<script src="script.js?v=4"></script>

</body>
</html>