create table SongInfo (
    id int not null auto_increment,
    title varchar(32) default null,
    artist varchar(32) default null,
    uploaderId int not null,
    uploaderName varchar(32) default null,
    uploadedOn timestamp default current_timestamp not null,
    bpm float not null,
    difficulties tinyint not null,
    primary key (id)
);
