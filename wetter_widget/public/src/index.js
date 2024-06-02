const weatherContainer = document.getElementById('weather-container');
const settingsContainer = document.getElementById('settings-container');

// Weather
const timeRefreshContainerElement = document.getElementById('time-refresh-container');
const timeElement = document.getElementById('time');
const refreshElement = document.getElementById('refresh-icon');
const refreshingElement = document.getElementById('refreshing-icon');

const cityElement = document.getElementById('city-name');
const skeletonWeatherIconElement = document.getElementById('skeleton-weather-icon');
const allWeatherIcons = document.querySelectorAll('.weather-icon');
const tempElement = document.getElementById('temperature');
const descriptionElement = document.getElementById('weather-description');
const windElement = document.getElementById('wind-val');
const humidityElement = document.getElementById('humidity-val');
const feelsLikeElement = document.getElementById('feels-like-val');

// Settings
const open_settings_icon = document.getElementById('open-settings-icon');
const close_settings_icon = document.getElementById('close-settings-icon');

const yourLocationSetting = document.getElementById('your-location');
const customLocationSetting = document.getElementById('custom-location');

const tempCelsiusSetting = document.getElementById('temp-celsius');
const tempFahrenheitSetting = document.getElementById('temp-fahrenheit');
const tempKelvinSetting = document.getElementById('temp-kelvin');

const kmhSetting = document.getElementById('kmh');
const mihSetting = document.getElementById('mih');

const timeMilitarySetting = document.getElementById('time-military');
const timeAmPmSetting = document.getElementById('time-am-pm');

const themeAuto = document.getElementById('theme-auto');
const themeLight = document.getElementById('theme-light');
const themeDark = document.getElementById('theme-dark');

const layoutVertical = document.getElementById('layout-vertical');
const layoutHorizontal = document.getElementById('layout-horizontal');

let city = 'Osaka';
let useUserLocation = true;
let timeMilitary = true;

const refresh = () => {
    refreshElement.style.display = 'none';
    refreshingElement.style.display = 'inline';

    setTimeout(() => {
        timeElement.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: !timeMilitary});

        if (useUserLocation === true) {
            fetchUserLocation()
                .then(location => {
                    console.log(`latitude: ${location.lat}\nlongitude: ${location.lon}`);
                    fetchWeatherData(location.lat, location.lon, null);
                })
                .catch(err => {
                    console.error(err);
                });
        } else if (useUserLocation === false) {
            fetchWeatherData(null, null, city);
        }

        refreshingElement.style.display = 'none';
        refreshElement.style.display = 'inline';
        skeletonWeatherIconElement.style.display = 'none';
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

const fetchUserLocation = () => {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                resolve({ lat, lon });
            },
            err => {
                reject(err);
            }
        );
    });
}

const fetchWeatherData = (lat, lon, city) => {
    const API_KEY = '6789d5a6b5cb0ce6f47e021e1fafbf6e';
    let url;

    if (lat !== undefined && lat !== null && lon !== undefined && lon !== null) {
        url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}`;
    } else if (city !== undefined) {
        url = `http://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}`;
    }
    
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
        
        allWeatherIcons.forEach(element => {
            element.style.display = 'none';
        });
        const weatherIcon = `icon-${data.weather[0].icon}`;
        document.getElementById(weatherIcon).style.display = 'inline';

        tempElement.innerHTML = '';
        tempElement.innerHTML += `${temperature}<sup>°C</sup></sup>`;
        
        descriptionElement.innerHTML = '';
        descriptionElement.textContent = `${toTitleCase(weatherDescription)}`;

        windElement.innerHTML = '';
        windElement.innerHTML = `${data.wind.speed}<span class="details-unit" id="wind-unit">KPH</span>`

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

yourLocationSetting.addEventListener('click', () => {
    if (yourLocationSetting.className !== 'selected-double-button') {
        yourLocationSetting.className = 'selected-double-button';
        customLocationSetting.className = 'unselected-double-button';
        useUserLocation = true;
    } else {
        useUserLocation = true;
        console.log('Setting already selected');
    }
});

customLocationSetting.addEventListener('click', () => {
    if (customLocationSetting.className !== 'selected-double-button') {
        customLocationSetting.className = 'selected-double-button';
        yourLocationSetting.className = 'unselected-double-button';
        useUserLocation = false;
    } else {
        useUserLocation = false;
        console.log('Setting already selected');
    }
});

tempCelsiusSetting.addEventListener('click', () => {
    if (tempCelsiusSetting.className !== 'selected-tripple-button') {
        tempCelsiusSetting.className = 'selected-tripple-button';
        tempFahrenheitSetting.className = 'unselected-tripple-button';
        tempKelvinSetting.className = 'unselected-tripple-button';
    } else {
        tempFahrenheitSetting.className = 'unselected-tripple-button';
        tempKelvinSetting.className = 'unselected-tripple-button';
        console.log('Setting already selected');
    }
});

tempFahrenheitSetting.addEventListener('click', () => {
    if (tempFahrenheitSetting.className !== 'selected-tripple-button') {
        tempFahrenheitSetting.className = 'selected-tripple-button';
        tempCelsiusSetting.className = 'unselected-tripple-button';
        tempKelvinSetting.classList = 'unselected-tripple-button';
    } else {
        tempCelsiusSetting.className = 'unselected-tripple-button';
        tempKelvinSetting.classList = 'unselected-tripple-button';
        console.log('Setting already selected');
    }
});

tempKelvinSetting.addEventListener('click', () => {
    if (tempKelvinSetting.className !== 'selected-tripple-button') {
        tempKelvinSetting.className = 'selected-tripple-button';
        tempCelsiusSetting.className = 'unselected-tripple-button';
        tempFahrenheitSetting.className = 'unselected-tripple-button';
    } else {
        tempCelsiusSetting.className = 'unselected-tripple-button';
        tempFahrenheitSetting.className = 'unselected-tripple-button';
        console.log('Setting already selected');
    }
});

kmhSetting.addEventListener('click', () => {
    if (kmhSetting.className !== 'selected-double-button') {
        kmhSetting.className = 'selected-double-button';
        mihSetting.className = 'unselected-double-button';
    } else {
        mihSetting.className = 'unselected-double-button';
        console.log('Setting already selected');
    }
});

mihSetting.addEventListener('click', () => {
    if (mihSetting.className !== 'selected-double-button') {
        mihSetting.className = 'selected-double-button';
        kmhSetting.className = 'unselected-double-button';
    } else {
        kmhSetting.className = 'unselected-double-button';
        console.log('Setting already selected');
    }
});

timeMilitarySetting.addEventListener('click', () => {
    if (timeMilitarySetting.className !== 'selected-double-button') {
        timeMilitarySetting.className = 'selected-double-button';
        timeAmPmSetting.className = 'unselected-double-button';
        timeMilitary = true;
    } else {
        timeAmPmSetting.className = 'unselected-double-button';
        timeMilitary = true;
        console.log('Setting already selected');
    }
});

timeAmPmSetting.addEventListener('click', () => {
    if (timeAmPmSetting.className !== 'selected-double-button') {
        timeAmPmSetting.className = 'selected-double-button';
        timeMilitarySetting.className = 'unselected-double-button';
        timeMilitary = false;
    } else {
        timeMilitarySetting.className = 'unselected-double-button';
        timeMilitary = false;
        console.log('Setting already selected');
    }
});

themeAuto.addEventListener('click', () => {
    if (themeAuto.className !== 'selected-tripple-button') {
        themeAuto.className = 'selected-tripple-button';
        themeLight.className = 'unselected-tripple-button';
        themeDark.className = 'unselected-tripple-button'
    } else {
        themeLight.className = 'unselected-tripple-button';
        themeDark.className = 'unselected-tripple-button';
        console.log('Setting already selected')
    }
});

themeLight.addEventListener('click', () => {
    if (themeLight.className !== 'selected-tripple-button') {
        themeLight.className = 'selected-tripple-button';
        themeAuto.className = 'unselected-tripple-button';
        themeDark.className = 'unselected-tripple-button'
    } else {
        themeAuto.className = 'unselected-tripple-button';
        themeDark.className = 'unselected-tripple-button';
        console.log('Setting already selected')
    }
});

themeDark.addEventListener('click', () => {
    if (themeDark.className !== 'selected-tripple-button') {
        themeDark.className = 'selected-tripple-button';
        themeAuto.className = 'unselected-tripple-button';
        themeLight.className = 'unselected-tripple-button'
    } else {
        themeAuto.className = 'unselected-tripple-button';
        themeLight.className = 'unselected-tripple-button';
        console.log('Setting already selected')
    }
});

layoutVertical.addEventListener('click', () => {
    if (layoutVertical.className !== 'selected-double-button') {
        layoutVertical.className = 'selected-double-button';
        layoutHorizontal.className = 'unselected-double-button';
    } else {
        layoutHorizontal.className = 'unselected-double-button';
        console.log('Setting already selected');
    }
});

layoutHorizontal.addEventListener('click', () => {
    if (layoutHorizontal.className !== 'selected-double-button') {
        layoutHorizontal.className = 'selected-double-button';
        layoutVertical.className = 'unselected-double-button';
    } else {
        layoutVertical.className = 'unselected-double-button';
        console.log('Setting already selected');
    }
});

refresh();