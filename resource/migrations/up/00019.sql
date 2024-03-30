-- Migrate to Version 19 

set time_zone = '+0:00';
alter table journeys
    modify `boarding time stamp` datetime not null,
    add `boarding time` datetime as (
        date_add(`boarding time stamp`, interval `boarding time offset minutes` minute)
    )
        stored after `boarding time offset minutes`,
    add index (`boarding time`),
    modify `alighting time stamp` datetime not null,
    add `alighting time` datetime as (
        date_add(`alighting time stamp`, interval `alighting time offset minutes` minute)
    )
        stored after `alighting time offset minutes`,
    add index (`alighting time`);