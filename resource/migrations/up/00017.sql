alter table journeys
    modify type enum('Aeroplane','Helicopter','Train','Metro','Tram','Funicular','Coach','BRT','Bus','Trolleybus','Rail replacement','Share taxi','Ferry','Cable Car') not null