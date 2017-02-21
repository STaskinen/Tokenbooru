create table BO_aliases (
			oldtag VARCHAR(128) NOT NULL,
			newtag VARCHAR(128) NOT NULL,
			PRIMARY KEY (oldtag)
		)ENGINE=MYISAM;
		
create table BO_config (
			uname VARCHAR(128) NOT NULL,
			cvalue TEXT,
			PRIMARY KEY (uname)
		)ENGINE=MYISAM;
        
create table BO_users (
			id INT(20) PRIMARY KEY NOT NULL,
			uname VARCHAR(32) UNIQUE NOT NULL,
			pass VARCHAR(250),
			joindate DATETIME NOT NULL,
			class VARCHAR(32) NOT NULL DEFAULT 'user',
			email VARCHAR(128)
		)ENGINE=MYISAM;
		
create table BO_images (
			id INT PRIMARY KEY NOT NULL,
			owner_id INT(20) NOT NULL,
			filename VARCHAR(64) NOT NULL,
			filesize INTEGER NOT NULL,
			ext CHAR(4) NOT NULL,
			isource VARCHAR(255),
			width INTEGER NOT NULL,
			height INTEGER NOT NULL,
            score INT NOT NULL DEFAULT 1,
			posted DATETIME NOT NULL,
			locked BOOLEAN NOT NULL DEFAULT FALSE,
			FOREIGN KEY (owner_id) REFERENCES BO_users(id) ON DELETE RESTRICT
		)ENGINE=MYISAM;
		
create table BO_tags (
			id INT PRIMARY KEY NOT NULL,
			tag VARCHAR(64) UNIQUE NOT NULL,
			tcount INTEGER NOT NULL DEFAULT 0
		)ENGINE=MYISAM;
		
create table BO_image_tags (
			image_id INTEGER NOT NULL,
			tag_id INTEGER NOT NULL,
			UNIQUE(image_id, tag_id),
			FOREIGN KEY (image_id) REFERENCES BO_images(id) ON DELETE CASCADE,
			FOREIGN KEY (tag_id) REFERENCES BO_tags(id) ON DELETE CASCADE
		)ENGINE=MYISAM;
        

drop table BO_image_tags;
drop table BO_images;
drop table BO_users;
drop table BO_aliases;
drop table BO_config;
drop table BO_tags;


INSERT INTO BO_users(
id,
uname,
joindate,
class
)VALUES(
12354,
'Matikainen',
'2013-08-30 19:05:00',
'master'
);

insert into BO_aliases(
)VALUES (
    'finger',
	'poker'
);

INSERT INTO BO_config()
VALUES(
'Matikainen',
'Ima text yo'
);

INSERT INTO BO_images (
id,
owner_id,
filename,
filesize,
ext,
width,
height,
posted,
locked
)
VALUES(
45413246534,
12354,
    'Matikaisenkesäpäivä',
    1048576,
    'png',
    1280,
    720,
    '2013-08-30 19:05:35',
    FALSE
);

INSERT INTO BO_tags()
VALUES(
    1,
    'kesä',
    1
),(
    2,
    'päivä',
    1
);

INSERT INTO BO_image_tags ()
VALUES (    
    45413246534,
    1
),(
    45413246534,
    2
);