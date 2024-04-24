const cityElement = document.getElementById('city-name');
const tempElement = document.getElementById('temperature');
const descriptionElement = document.getElementById('weather-description');
const windElement = document.getElementById('wind-val');
const humidityElement = document.getElementById('humidity-val');
const feelsLikeElement = document.getElementById('feels-like-val');

const API_KEY = '6789d5a6b5cb0ce6f47e021e1fafbf6e';

const toTitleCase = (str) => {
return str.replace(/\w\S*/g, (txt) => {
    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
});
}

const fetchWeatherData = (city) => {
    const url = `http://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}`;    

    fetch(url, {
        method: 'POST',
        body: JSON.stringify({ city: city })
    })
    .then(res => res.json())
    .then(data => {
        const city = data.name;
        const temperature = Math.floor(data.main.temp - 273.15);
        const weatherDescription = data.weather[0].description;

        cityElement.innerHTML = ''; // Vorherige Inhalte loeschen
        cityElement.textContent += `${city}`;
        
        const weatherIcon = `icon-${data.weather[0].icon}`;
        console.log(weatherIcon);
        document.getElementById(weatherIcon).style.display = 'inline';

        console.log(data.weather.icon);

        tempElement.innerHTML = '';
        tempElement.innerHTML += `${temperature}<sup>°C</sup></sup>`;
        
        descriptionElement.innerHTML = '';
        descriptionElement.textContent = `${toTitleCase(weatherDescription)}`;

        windElement.innerHTML = '';
        windElement.innerHTML = `${data.wind.speed}<sup class="details-unit" id="wind-unit">km/h</sup>`

        humidityElement.innerHTML = '';
        humidityElement.innerHTML = `${data.main.humidity}<sup class="details-unit" id="humidity-unit">%</sup>`;

        feelsLikeElement.innerHTML = '';
        feelsLikeElement.innerHTML = `${(data.main.feels_like-273.15).toFixed(1)}<sup class="details-unit" id="feels-like-unit">°C</sup>`;

        console.log(data);
    })
    .catch(err => {
        console.error('Error fetching weather data:', err);
    });
}

fetchWeatherData('Osaka');