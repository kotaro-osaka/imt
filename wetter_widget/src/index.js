const weatherContainer = document.getElementById('weather-container');
const settingsContainer = document.getElementById('settings-container');

const timeRefreshContainerElement = document.getElementById('time-refresh-container');
const timeElement = document.getElementById('time');
const refreshElement = document.getElementById('refresh-icon');
const refreshingElement = document.getElementById('refreshing-icon');

const open_settings_icon = document.getElementById('open-settings-icon');
const close_settings_icon = document.getElementById('close-settings-icon');

const cityElement = document.getElementById('city-name');
const skeletonWeatherIconElement = document.getElementById('skeleton-weather-icon');
const tempElement = document.getElementById('temperature');
const descriptionElement = document.getElementById('weather-description');
const windElement = document.getElementById('wind-val');
const humidityElement = document.getElementById('humidity-val');
const feelsLikeElement = document.getElementById('feels-like-val');

const API_KEY = '6789d5a6b5cb0ce6f47e021e1fafbf6e';

const refresh = () => {
    refreshElement.style.display = 'none';
    refreshingElement.style.display = 'inline';

    setTimeout(() => {
        timeElement.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});

        // fetchWeatherData('Osaka');

        refreshingElement.style.display = 'none';
        refreshElement.style.display = 'inline';
        // skeletonWeatherIconElement.style.display = 'none';
    }, 2000);
}

const openSettings = () => {
    weatherContainer.style.display = 'none';
    settingsContainer.style.display = 'flex';
}

const closeSettings = () => {
    settingsContainer.style.display = 'none';
    weatherContainer.style.display = 'flex';

    refresh();
}

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

        console.log(data.weather[0].icon);

        tempElement.innerHTML = '';
        tempElement.innerHTML += `${temperature}<sup>°C</sup></sup>`;
        
        descriptionElement.innerHTML = '';
        descriptionElement.textContent = `${toTitleCase(weatherDescription)}`;

        windElement.innerHTML = '';
        windElement.innerHTML = `${data.wind.speed}<span class="details-unit" id="wind-unit">km/h</span>`

        humidityElement.innerHTML = '';
        humidityElement.innerHTML = `${data.main.humidity}<span class="details-unit" id="humidity-unit">%</span>`;

        feelsLikeElement.innerHTML = '';
        feelsLikeElement.innerHTML = `${(data.main.feels_like-273.15).toFixed(1)}<sup class="details-unit" id="feels-like-unit">°C</sup>`;

        console.log(data);
    })
    .catch(err => {
        console.error('Error fetching weather data:', err);
        document.getElementById('skeleton-weather-icon').style.display = 'block';
    });
}

timeRefreshContainerElement.addEventListener('click', refresh);

open_settings_icon.addEventListener('click', openSettings);
close_settings_icon.addEventListener('click', closeSettings);


refresh();