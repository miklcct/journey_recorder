-- Migrate to Version 15 
ALTER TABLE `ticket uses`
    CHANGE `carnet sequence` `carnet sequence` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    DROP PRIMARY KEY,
    ADD PRIMARY KEY(
        `journey serial`,
        `ticket serial`,
        `carnet sequence`
    );
