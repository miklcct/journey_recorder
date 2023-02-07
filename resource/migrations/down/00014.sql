-- Migrate to Version 14 
ALTER TABLE `ticket uses`
    CHANGE `carnet sequence` `carnet sequence` INT(10) UNSIGNED NOT NULL,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY(
        `journey serial`,
        `ticket serial`
    );
