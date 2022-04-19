-- Migrate to Version 8
alter view `ticket apportion`
    as select `ticket uses`.`ticket serial` as `ticket serial`
        , `ticket uses`.`journey serial` as `journey serial`
        , tickets.currency as currency
        , ifnull(
            `ticket uses`.`distance covered`
            , (
                select journeys.distance
                from journeys
                where journeys.serial = `ticket uses`.`journey serial`
            )
        ) / summary.`total distance covered` * summary.`price per carnet`
            as fare
    from `ticket uses`
        left join tickets
            on `ticket uses`.`ticket serial` = tickets.serial
        left join journeys
            on `ticket uses`.`journey serial` = journeys.serial
        left join (
            select `ticket uses`.`ticket serial` as `ticket serial`
                , `ticket uses`.`carnet sequence` as `carnet sequence`
                , tickets.price / tickets.carnets as `price per carnet`
                , sum(
                    ifnull(
                        `ticket uses`.`distance covered`
                        , (
                            select journeys.distance
                            from journeys
                            where journeys.serial = `ticket uses`.`journey serial`
                        )
                    )
                ) as `total distance covered`
            from `ticket uses`
                left join tickets on `ticket uses`.`ticket serial` = tickets.serial
            group by `ticket uses`.`ticket serial`, `ticket uses`.`carnet sequence`
        ) summary
            on `ticket uses`.`ticket serial` = summary.`ticket serial`
                and `ticket uses`.`carnet sequence` = summary.`carnet sequence`