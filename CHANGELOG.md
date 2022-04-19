# Changelog
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