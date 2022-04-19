-- Migrate to Version 7 
alter table `ticket uses`
    modify column `carnet sequence` int unsigned null default null