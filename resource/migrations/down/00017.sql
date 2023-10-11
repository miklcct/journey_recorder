DELIMITER $$
CREATE OR REPLACE TRIGGER `validate ticket use before update` BEFORE UPDATE ON `ticket uses` FOR EACH ROW begin
    declare distance decimal(7, 2);
    select journeys.distance into distance from journeys where serial = new.`journey serial`;

    if new.`cover from` >= ifnull(distance, 0) or new.`cover to` > ifnull(distance, 0)
    then
        signal sqlstate '23000' set message_text = 'Covered distance is out of bounds.';
    end if;

    if new.`carnet sequence` >= (select tickets.carnets from tickets where serial = new.`ticket serial`)
    then
        signal sqlstate '23000' set message_text = 'Carnet sequence is out of bounds.';
    end if;
end
$$
CREATE OR REPLACE TRIGGER `validate ticket use before insert` BEFORE INSERT ON `ticket uses` FOR EACH ROW begin
declare distance decimal(7, 2);
select journeys.distance into distance from journeys where serial = new.`journey serial`;

if new.`cover from` >= ifnull(distance, 0) or new.`cover to` > ifnull(distance, 0)
then
    signal sqlstate '23000' set message_text = 'Covered distance is out of bounds.';
end if;

if new.`carnet sequence` >= (select tickets.carnets from tickets where serial = new.`ticket serial`)
then
    signal sqlstate '23000' set message_text = 'Carnet sequence is out of bounds.';
end if;
end
$$
DELIMITER ;

alter table `ticket uses`
    add primary key (`journey serial`, `ticket serial`, `carnet sequence`);

drop index `ticket uses unique` on `ticket uses`;

alter table `ticket uses`
    drop `instance`