-- Migrate to Version 10 
alter view `tickets view` as
    select serial
        , description
        , advance
        , currency
        , price
        , carnets
        , expired
        , `first use`
        , `last use`
        , `carnets used`
        , `segments travelled`
        , `distance travelled`
        , price / carnets * `carnets used` as `price used`
        , price / carnets * `carnets used` / `distance travelled` as `price per km`
    from tickets join (
        select serial as `_serial`
        , (
            select min(`boarding time stamp`)
            from `ticket uses` join journeys on `journey serial` = serial
            where `ticket serial` = tickets.serial
        ) as `first use`
        , (
            select max(`alighting time stamp`)
            from `ticket uses` join journeys on `journey serial` = serial
            where `ticket serial` = tickets.serial
        ) as `last use`
        , (
            select count(distinct `carnet sequence`)
            from `ticket uses`
            where `ticket serial` = tickets.serial
        ) as `carnets used`
        , (
            select count(distinct `journey serial`)
            from `ticket uses`
            where `ticket serial` = tickets.serial
        ) as `segments travelled`
        , (
            select ifnull(sum(ifnull(`distance covered`, distance)),0)
            from `ticket uses` join journeys on `journey serial` = serial
            where `ticket serial` = tickets.serial
        ) as `distance travelled`
        from tickets
    ) as `tickets info` on tickets.serial = `tickets info`._serial
    order by `tickets info`.`last use` desc