-- Migrate to Version 13 

alter table journeys
    drop constraint `check boarding is not later than alighting`