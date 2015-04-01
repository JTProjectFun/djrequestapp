CREATE TABLE IF NOT EXISTS settings(session_timeout int default 120, flood_period int default 30,over_hours int default 2,willexpire int default 1,show_requests int default 1, baseURL varchar(64);
CREATE TABLE IF NOT EXISTS requestkeys (id int NOT NULL AUTO_INCREMENT, timedate varchar(32),thekey varchar(8) NOT NULL, date varchar(32), showrequests int(1), willexpire int(1), PRIMARY KEY(id));
CREATE TABLE IF NOT EXISTS requests(id int NOT NULL AUTO_INCREMENT,timedate varchar(32),thekey NOT NULL varchar(8),name varchar(64),artist varchar(64),title varchar(64),message varchar(140),willplay int,played int,ipaddr varchar(20),uniqueid varchar(64),PRIMARY KEY(id));

