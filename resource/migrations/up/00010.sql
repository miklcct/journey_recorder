-- Migrate to Version 10 
alter view `journeys fare` as
    select `boarding time stamp`
        , type
        , network
        , route
        , destination
        , `boarding place`
        , `alighting place`
        , distance
        , `time taken`
        , speed
        , `is fully ticketed`(serial) as `fully ticketed`
        , `get tickets count`(serial) as `tickets count`
        , (
           select currency
           from `ticket apportion`
           where `journey serial` = serial
           limit 1
        ) as currency
        , (
           select sum(fare)
           from `ticket apportion`
           where `journey serial` = serial
        ) as fare
        , exists(
            select 0
            from `ticket apportion`
            where `journey serial` = serial
                and (
                    select advance
                    from tickets
                    where serial = `ticket serial`
                )
        ) as advance
        , exists(
            select 0
            from `ticket uses`
            where `journey serial` = serial
                and `group size` <> 1
        ) as `group`
        , (
            select sum(fare)
            from `ticket apportion`
            where `journey serial` = serial
        ) / distance as `fare per km`
    from journeys
    where `is fully ticketed`(serial)
        and (
            select count(distinct ifnull(currency, ''))
            from `ticket apportion`
            where `journey serial` = serial
        ) = 1
    order by `boarding time stamp` desc;
