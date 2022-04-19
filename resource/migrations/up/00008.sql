-- Migrate to Version 8 
alter table `ticket uses`
    modify column `carnet sequence` int unsigned not null