-- Migrate to Version 5 
alter table `ticket uses`
    modify column `carnet sequence` int;
