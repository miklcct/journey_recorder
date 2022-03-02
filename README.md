# Journey Recorder

This application helps you to record your public transport journeys while on the go.

## Installation
### As a standalone application
1. Load the schema provided in `resource/transport.sql` into a MariaDB database.
2. Configure your web server to have `public` as the document root.

### As a library used in a website
1. Load the schema provided in `resource/transport.sql` into a MariaDB database.
2. Link the script provided in `public/scripts` into the `scripts` folder of your document root.
3. Copy or link the stylesheet provided in `public/stylesheets` into the `stylesheets` folder of your document root,
making any changes if needed to fit into your website.
4. Provide your implementation of `JourneyResponseFactoryInterface` such that the UI fits into your website.
The `JourneyView` class and `journey_main.xhtml.php` template is a good starting point.
5. Add a route in your website to have `JourneyApplication`, which is PSR-15 compliant, as the controller.

## Usage
This application is for recording journeys only.
It doesn't provide any interfaces for later retrieval (except the latest one)
or analysis tools.

It allows you to enter the information for a public transport journey, including the distance, fare and tickets.
It supports multiple tickets and split tickets.
If a distance is not provided, it will attempt to fill it from a previous identical journey.

The ticket list is populated from the previous submission. 
If no tickets are shown, use the "Get the last inserted journey" button to load them.

The "push into queue" and "pop from queue" buttons are provided to save the form and load it later
when it's not ready for submission, or in situations when you are temporarily lacking internet access
(such as in the Tube).
