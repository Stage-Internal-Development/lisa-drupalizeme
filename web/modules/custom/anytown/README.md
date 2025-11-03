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
