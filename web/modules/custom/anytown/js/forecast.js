(function (Drupal, once) {
  Drupal.behaviors.forecastToggle = {
    attach: function (context, settings) {
      // Use 'once' to ensure this runs only once per context
      once('forecast-toggle', 'div.weather-page__forecast', context).forEach(function (el) {
        // Initialize: hide 'div.long' and show 'div.short'.
        const long = el.querySelector('.weather-page__forecast--extended');
        const short = el.querySelector('.weather-page__forecast--short');
        long.classList.add('visually-hidden');

        // Create and configure a button to toggle between thet wo.
        const toggleButton = document.createElement('button');
        toggleButton.textContent = 'Show extended forecast';
        toggleButton.addEventListener('click', function () {
          long.classList.toggle('visually-hidden');
          short.classList.toggle('visually-hidden');
          if(long.classList.contains('visually-hidden')) {
            toggleButton.textContent = Drupal.t('Show extended forecast');
          } else {
            toggleButton.textContent = Drupal.t('Show short forecast');
          }
        });

        // Append the button to the page.
        document.querySelector('.weather-page__forecast').appendChild(toggleButton);
      });
    }
  };
})(Drupal, once);
