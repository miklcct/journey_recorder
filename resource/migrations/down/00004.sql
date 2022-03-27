-- Migrate to Version 4 
alter table journeys
    modify column distance decimal(5,2);