# Journey Recorder

This application helps you to record your public transport journeys while on the go.

## Installation
### As a standalone application
1. Load the schema provided in `resource/base.sql` and all migrations in `resource/migrations/up` into a MariaDB database.
2. Configure your web server to have `public` as the document root, or link a subfolder
under the document root to the `public` folder.

### As a library used in a website
1. Load the schema provided in `resource/base.sql` and all migrations in `resource/migrations/up` into a MariaDB database.
2. Provide your implementation of `JourneyResponseFactoryInterface` such that the UI fits into your website.
The `JourneyView` class and `journey_main.xhtml.php` template is a good starting point.
If you are using them, link `scripts/journey.js` and `stylesheets/journey.css` into your public folder
and pass the web path into the `JourneyView` constructor.
3. If you want the offline functionality, link `journey_service_worker.js` into your public folder as well and register
it in your script. The provided `scripts/journey.js` does that, in this case it must be put in the root of the public folder.
4. Add a route in your website to have `JourneyApplication`, which is PSR-15 compliant, as the controller.

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

If the browser supports service workers and the default script is used, the script registers a service worker to cache
resources needed for the application, such that it is usable while offline. Also, the submit buttons are disabled while
offline.

## Database migrations
The migration files are organised in the structure required by `byjg/migration` package.
However, the use of the library is not necessary. If you want to use it for your database(s),
install the CLI interface by running `composer global require byjg/migration-cli`.

## Defects
Some database column names may be misleading. However, to prevent compatibility issues, they will only be fixed in the
next major release:
* The database should be unit agnostic on distance but some columns assume the use of km as the unit of distance:
  * `tickets view`.`price per km`
  * `journeys fare`.`fare per km`

  These should all mean per distance instead of per km to allow the use of alternative distance units.
* `tickets view`.`distance travelled` means person-distance travelled, while `tickets view`.`segments travelled` means 
segments travelled regardless of number of people using that ticket
* `advance` does not adequately cover all pre-purchase requirement:
  * Quota-controlled tickets are sold against a quota. They are not guaranteed to be available.
    * A ticket is quota-controlled if it must be bought against a specific service and not valid on any others without changing it,
    even if the price never changes, as walk-up travel is not guaranteed.
  * Advance purchase non-quota-controlled tickets are always available before a specified deadline, but not immediately before travel.
  * Walk up tickets are guaranteed to be available at the time of travel.

## Demos
The following demos can be used for testing, but due to privacy concern
(data will go through servers under author's control) they are not suggested
being used in production.

* [Standalone site](https://journey.miklcct.com/)
* [Part of another website](https://miklcct.com/journey)