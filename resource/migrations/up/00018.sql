alter table `ticket uses`
    add `instance` decimal(7, 2) as (ifnull(`cover from`, -1)) stored;

create unique index `ticket uses unique`
    on `ticket uses` (`journey serial`, `ticket serial`, `carnet sequence`, instance);

alter table `ticket uses`
    drop primary key;

delimiter $$
create or replace trigger `validate ticket use before insert` before insert on `ticket uses` for each row begin
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

    if exists(
        select 0
        from `ticket uses`
        where `journey serial` = new.`journey serial`
            and `ticket serial` = new.`ticket serial`
            and `carnet sequence` = new.`carnet sequence`
            and (`cover from` is null or new.`cover from` is null or `cover from` < new.`cover to` and `cover to` > new.`cover from`)
    )
    then
        signal sqlstate '23000' set message_text = 'Overlapping distance on the same ticket carnet';
    end if;
end
$$
create or replace trigger `validate ticket use before update` before update on `ticket uses` for each row begin
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

    if exists(
        select 0
        from `ticket uses`
        where `journey serial` = new.`journey serial`
            and `ticket serial` = new.`ticket serial`
            and `carnet sequence` = new.`carnet sequence`
            and not (
                `journey serial` = old.`journey serial`
                and `ticket serial` = old.`ticket serial`
                and `carnet sequence` = old.`carnet sequence`
                and instance = old.instance
            )
            and (`cover from` is null or new.`cover from` is null or `cover from` < new.`cover to` and `cover to` > new.`cover from`)
    )
    then
        signal sqlstate '23000' set message_text = 'Overlapping distance on the same ticket carnet';
    end if;
end
$$
delimiter ;