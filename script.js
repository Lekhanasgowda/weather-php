console.log("JS LOADED ✅");

const apiKey = "0e3d18118b337d28f2b2e58525380325";

let currentCity = "";
let chartInstance = null;

// Get Weather
function getWeather() {
    const city = document.getElementById("cityInput").value;

    if (!city) {
        alert("Please enter a city");
        return;
    }

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`)
    .then(res => res.json())
    .then(data => {
        if (data.cod !== 200) {
            alert("City not found");
            return;
        }
        displayWeather(data);
    })
    .catch(() => alert("Error fetching weather"));
}

// Display Weather
function displayWeather(data) {

    if (!data || !data.main || !data.weather) {
        alert("Invalid weather data");
        return;
    }

    currentCity = data.name;

    document.getElementById("weatherCard").style.display = "block";

    document.getElementById("cityName").innerText = data.name;
    document.getElementById("temp").innerText = data.main.temp + "°C";
    document.getElementById("condition").innerText = data.weather[0].description;

    document.getElementById("humidity").innerText = "💧 " + data.main.humidity;
    document.getElementById("wind").innerText = "🌬 " + data.wind.speed;
    document.getElementById("feels").innerText = "🤒 " + data.main.feels_like;
    document.getElementById("pressure").innerText = "🌡 " + data.main.pressure + " hPa";

    document.getElementById("icon").src =
        `https://openweathermap.org/img/wn/${data.weather[0].icon}.png`;

    // 🎨 Background color
    setBackground(data.weather[0].main);

    // 💡 Suggestion
    setSuggestion(data);

    // 🌦 REMOVE all previous animations
    document.body.classList.remove(
        "rain",
        "sunny",
        "cloudy",
        "snow",
        "thunder",
        "mist"
    );

    // 🌈 Apply animation based on weather
    const weather = data.weather[0].main;

    if (weather.includes("Rain")) {
        document.body.classList.add("rain");
    }
    else if (weather.includes("Cloud")) {
        document.body.classList.add("cloudy");
    }
    else if (weather.includes("Clear")) {
        document.body.classList.add("sunny");
    }
    else if (weather.includes("Snow")) {
        document.body.classList.add("snow");
    }
    else if (weather.includes("Thunderstorm")) {
        document.body.classList.add("thunder");
    }
    else if (weather.includes("Mist") || weather.includes("Fog") || weather.includes("Haze")) {
        document.body.classList.add("mist");
    }

    // 📊 Forecast
    if (data.coord && data.coord.lat && data.coord.lon) {
        fetchForecast(data.coord.lat, data.coord.lon);
    }
}

// Background
function setBackground(weather) {
    if (weather.includes("Rain")) {
        document.body.style.background = "#4e54c8";
    } else if (weather.includes("Cloud")) {
        document.body.style.background = "#757f9a";
    } else {
        document.body.style.background = "#f7971e";
    }
}

// Suggestions
function setSuggestion(data) {
    let msg = "";

    if (data.main.temp > 35)
        msg = "🔥 Too hot! Stay hydrated";
    else if (data.main.temp < 15)
        msg = "🧥 Cold! Wear warm clothes";
    else if (data.weather[0].main.includes("Rain"))
        msg = "☔ Carry umbrella";
    else
        msg = "🌤 Nice weather!";

    document.getElementById("suggestion").innerText = msg;
}

// Forecast
function fetchForecast(lat, lon) {

    fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`)
    .then(res => res.json())
    .then(data => {

        const f = document.getElementById("forecast");
        if (!f) return;

        f.innerHTML = "";

        let temps = [];

        data.list.slice(0, 8).forEach(i => {
            temps.push(i.main.temp);
            f.innerHTML += `<div>${i.main.temp}°C</div>`;
        });

        drawChart(temps);
    });
}

// Chart (FIXED: no duplicate chart issue)
function drawChart(temps) {
    const ctx = document.getElementById("chart");
    if (!ctx) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
        type: "line",
        data: {
            labels: temps.map((_, i) => i + 1),
            datasets: [{
                label: "Temperature (°C)",
                data: temps,
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });
}

// Location
function getLocationWeather() {

    if (!navigator.geolocation) {
        alert("Geolocation not supported");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        position => {

            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`)
            .then(res => res.json())
            .then(displayWeather);

        },
        () => alert("Location access denied")
    );
}

// 🌙 Dark Mode
function toggleTheme() {
    document.body.classList.toggle("dark");
}

// ⭐ Favorites
function saveFavorite() {
    let fav = JSON.parse(localStorage.getItem("fav")) || [];

    if (!fav.includes(currentCity)) {
        fav.push(currentCity);
        localStorage.setItem("fav", JSON.stringify(fav));
        loadFavorites();
    }
}

function loadFavorites() {
    let fav = JSON.parse(localStorage.getItem("fav")) || [];
    const container = document.getElementById("favorites");
    if (!container) return;

    container.innerHTML = "";

    fav.forEach(city => {
        let div = document.createElement("div");
        div.innerText = city;
        div.onclick = () => {
            document.getElementById("cityInput").value = city;
            getWeather();
        };
        container.appendChild(div);
    });
}

window.onload = loadFavorites;

// 🎤 Voice Search
function startVoice() {

    if (!('webkitSpeechRecognition' in window)) {
        alert("Voice not supported");
        return;
    }

    const rec = new webkitSpeechRecognition();

    rec.onresult = e => {
        document.getElementById("cityInput").value = e.results[0][0].transcript;
        getWeather();
    };

    rec.start();
}