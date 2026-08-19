// Fetch real-time weather from Open-Meteo (no API key required)

// Map WMO weathercode to Material Symbols icon name
export const getWeatherIcon = (code: number): string => {
    if (code === 0) return 'wb_sunny'                   // Clear sky
    if (code <= 2) return 'partly_cloudy_day'          // Partly cloudy
    if (code === 3) return 'cloud'                      // Overcast
    if (code <= 49) return 'foggy'                      // Fog/mist
    if (code <= 67) return 'rainy'                      // Rain/drizzle
    if (code <= 77) return 'ac_unit'                    // Snow
    if (code <= 82) return 'rainy'                      // Rain showers
    if (code <= 99) return 'thunderstorm'               // Thunderstorm
    return 'wb_sunny'
}

export const getCachedWeather = () => {
    try {
        const cached = localStorage.getItem('duyha_weather_data')
        if (cached) {
            const parsed = JSON.parse(cached)
            return {
                temp: `${parsed.temp}°C`,
                icon: getWeatherIcon(parsed.code),
                desc: 'Duy Hà',
                wind: `${parsed.wind} M/S`,
                humidity: `${parsed.humidity}%`
            }
        }
        const tempStr = localStorage.getItem('duyha_weather_temp') || '29°C'
        return {
            temp: tempStr,
            icon: 'wb_sunny',
            desc: 'Duy Hà',
            wind: '2.5 M/S',
            humidity: '75%'
        }
    } catch (_) {
        return {
            temp: '29°C',
            icon: 'wb_sunny',
            desc: 'Duy Hà',
            wind: '2.5 M/S',
            humidity: '75%'
        }
    }
}

export const applyCachedWeatherToDOM = () => {
    const data = getCachedWeather()
    const headerTemp = document.getElementById('weather-temp-portal') || document.getElementById('weather-temp')
    const headerIcon = document.getElementById('weather-icon-portal') || document.getElementById('weather-icon')
    const headerDesc = document.getElementById('weather-desc-portal') || document.getElementById('weather-desc')
    if (headerTemp) headerTemp.textContent = data.temp
    if (headerIcon) headerIcon.textContent = data.icon
    if (headerDesc) headerDesc.textContent = data.desc

    const mapTemp = document.getElementById('weather-temp-map')
    const mapIcon = document.getElementById('weather-icon-map')
    const mapDesc = document.getElementById('weather-desc-map')
    const mapWind = document.getElementById('weather-wind-map') || document.getElementById('wind-speed')
    const mapHumidity = document.getElementById('weather-humidity-map') || document.getElementById('humidity')
    if (mapTemp) mapTemp.textContent = data.temp
    if (mapIcon) mapIcon.textContent = data.icon
    if (mapDesc) mapDesc.textContent = data.desc
    if (mapWind) mapWind.textContent = data.wind
    if (mapHumidity) mapHumidity.textContent = data.humidity
}

export const initWeatherWidget = async () => {
    const updateUI = (temp: number, code: number, wind: number, humidity: number) => {
        const tempStr = `${temp}°C`
        const iconStr = getWeatherIcon(code)

        // Update header elements (on Homepage and Subpages)
        const headerTemp = document.getElementById('weather-temp-portal') || document.getElementById('weather-temp')
        const headerIcon = document.getElementById('weather-icon-portal') || document.getElementById('weather-icon')
        const headerDesc = document.getElementById('weather-desc-portal') || document.getElementById('weather-desc')

        if (headerTemp) headerTemp.textContent = tempStr
        if (headerIcon) headerIcon.textContent = iconStr
        if (headerDesc) headerDesc.textContent = 'Duy Hà'

        // Update Map View Floating Widget elements
        const mapTemp = document.getElementById('weather-temp-map')
        const mapIcon = document.getElementById('weather-icon-map')
        const mapDesc = document.getElementById('weather-desc-map')
        const mapWind = document.getElementById('weather-wind-map') || document.getElementById('wind-speed')
        const mapHumidity = document.getElementById('weather-humidity-map') || document.getElementById('humidity')

        if (mapTemp) mapTemp.textContent = tempStr
        if (mapIcon) mapIcon.textContent = iconStr
        if (mapDesc) mapDesc.textContent = 'Duy Hà'
        if (mapWind) mapWind.textContent = `${wind} M/S`
        if (mapHumidity) mapHumidity.textContent = `${humidity}%`

        // Cache in localStorage for zero-lag initial render
        try {
            localStorage.setItem('duyha_weather_data', JSON.stringify({ temp, code, wind, humidity, timestamp: Date.now() }))
            localStorage.setItem('duyha_weather_temp', tempStr)
        } catch (_) {}
    }

    // Apply cached data immediately
    applyCachedWeatherToDOM()

    const fetchWeather = async () => {
        try {
            // Precise coordinates for Duy Ha (Duy Tien, Ha Nam): 20.60, 105.93
            const url = 'https://api.open-meteo.com/v1/forecast?latitude=20.60&longitude=105.93&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FBangkok'
            const res = await fetch(url)
            if (!res.ok) return
            const data = await res.json()

            if (data && data.current) {
                const temp = Math.round(data.current.temperature_2m)
                const code = data.current.weather_code ?? data.current.weathercode ?? 0
                const wind = Math.round(data.current.wind_speed_10m * 10) / 10
                const humidity = Math.round(data.current.relative_humidity_2m)

                updateUI(temp, code, wind, humidity)
            }
        } catch (e) {
            console.error('Weather fetch failed:', e)
        }
    }

    await fetchWeather()
    // Refresh every 10 minutes
    setInterval(fetchWeather, 10 * 60 * 1000)
}
