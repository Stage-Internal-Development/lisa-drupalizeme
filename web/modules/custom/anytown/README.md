## ForecastClient
A service class that fetches weather forecast data from external APIs,
acts as a bridge between the Drupal application and the API providers.

1. HTTP Communication
- Makes GET requests to weather API URLs using Guzzle HTTP client.
- Handles network errors gracefully.

2. Error Handling
- Catches HTTP exceptions (network failures, timeouts, invalid URLs)
- Logs errors for debugging
- Returns null when data cannot be retrieved

3. Data transformation
- Received raw JSON from APIs
- Delegates parsing to an adapter
- Returns standardized array format

### How it works
```
[Controller]
  ↓ calls getForecastData($url)
[ForecastClient]
  ↓ makes HTTP request
[Weather API]
  ↓ returns JSON
[ForecastClient]
  ↓ delegates to adapter
[Adapter]
  ↓ parses & standardizes
[ForecastClient]
  ↓ returns array
[Controller]
```

### getForecastData() method
Input:
- URL as a string, pointing to a weather API endpoint.
Output:
- Standardized forecast data with keys
  - weekday
  - description
  - high
  - low
  - icon
- Null if API is unreachable or returns invalid data

### Usage
The controller WeatherPage gets the services via dependency injection.
It calls the getForecastData method with a URL from the weather API.
It receives standardized data, loops through it to build the HTML output and renders an unordered list.

### Dependencies
- Guzzle HTTP Client `$httpClient` - Makes HTTP requests
- Logger `$logger` - Records errors to Drupal's logging system
- Adapter `$adapter` - Knows how to parse specific API formats
