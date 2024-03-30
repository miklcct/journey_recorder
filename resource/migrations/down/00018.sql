-- Migrate to Version 18 

set time_zone = '+0:00';
alter table journeys
    drop `boarding time`,
    drop `alighting time`,
    modify `boarding time stamp` timestamp not null,
    modify `alighting time stamp` timestamp not null;