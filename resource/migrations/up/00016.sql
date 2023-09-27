alter table journeys
    modify type enum('Aeroplane','Helicopter','Train','Metro','Tram','Funicular','Coach','BRT','Bus','Trolleybus','Share taxi','Ferry','Cable Car') default null