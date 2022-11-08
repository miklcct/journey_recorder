-- Migrate to Version 14 

alter table journeys
    add constraint `check boarding is not later than alighting` check (`boarding time stamp` <= `alighting time stamp`)