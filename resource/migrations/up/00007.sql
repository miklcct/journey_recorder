-- Migrate to Version 7 
alter table `ticket uses`
    add column `group size` int unsigned not null default 1 after `carnet sequence`
