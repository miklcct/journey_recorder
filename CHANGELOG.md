# Changelog
## [1.6.0-beta.7] - 2023-11-18
 - Change precision of the time from 1 minute to 1 second

## [1.6.0-beta.6] - 2023-10-11
 - Allow the use of the same ticket on the same journey under the following circumstances:
   - Different carnets of the same ticket may be used without distance restriction
   - The same carnet may be used on a journey provided that the distance ranges covered are distinct

## [1.6.0-beta.5] - 2023-09-27
 - Add rail replacement as a type

## [1.6.0-beta.4] - 2023-09-27
 - Add coach as a type

## [1.6.0-beta.3] - 2023-09-22
 - Limit the scope of service worker to the page only (for use as part of another website).

## [1.6.0-beta.2] - 2023-09-22
 - Save the journey being submitted and populate it on page reload unless submission succeeds.

## [1.6.0-beta.1] - 2023-09-21
 - First testing version with offline support (useful while on the tube)

## [1.5.0] - 2023-07-06
 - Add metro as a transport type in the UI (already supported in the database)

## [1.4.4] - 2023-02-07
 - Set `ticket uses.carnet sequence` as 0 by default, which is sensible for
   non carnet tickets.
 - Change the primary key of `ticket uses` to include `carnet sequence` to
   allow using multiple carnets of the same ticket for a single journey.

## [1.4.3] - 2022-11-08
 - Add constraint `check boarding is not later than alighting`
 - Fix session loss after failed request

## [1.4.2] - 2022-10-10
 - Fix session loss despite daily use.

## [1.4.1] - 2022-04-19
 - Add serial into `journeys fare`

## [1.4.0] - 2022-04-19
 - Fix `carnet sequence` field in `ticket uses` table which should be not nullable.
 - Add group ticket support.

## [1.3.2] - 2022-04-07
 - Fix `carnet sequence` field in `ticket uses` table which should be unsigned.
   This prevents new tickets to be used without a new carnet.

## [1.3.1] - 2022-03-30
 - Fix PHP error submitting form with disabled fields.
 - Fix fields not enabling / disabling correctly when popping / pushing journeys.

## [1.3.0] - 2022-03-28
 - Add advance as a ticket property.
 - Fix distance column width in database to handle 5 integral digits and 2 decimal digits.
 - Require ticket details in client side validation when creating a new ticket.
 - Disable ticket creation fields when a ticket is selected.

## [1.2.0] - 2022-03-02
 - Fix handling of non-fixed-digit currencies.
 - Allow customisation of stylesheet and script paths.
 - Add explanations to the form.

## [1.1.0] - 2022-03-02
 - Store the host, port and database into the session and auto fill it when loading the form.
 - Store the version into the session and clear it when it is changed.

## [1.0.0] - 2022-03-02
- Initial release
