-- Migrate to Version 2 
alter table tickets
    add column advance
        boolean
        not null
        default false
        comment 'if the ticket requires quota-controlled reservation'
        after description