alter table journeys
    modify type enum('Aeroplane','Helicopter','Train','Metro','Tram','Funicular','BRT','Bus','Trolleybus','Share taxi','Ferry','Cable Car') not null