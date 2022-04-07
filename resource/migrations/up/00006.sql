-- Migrate to Version 6 
alter table `ticket uses`
    modify column `carnet sequence` int unsigned;
