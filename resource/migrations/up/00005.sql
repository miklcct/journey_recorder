-- Migrate to Version 5
alter table journeys
    modify column distance decimal(7,2);