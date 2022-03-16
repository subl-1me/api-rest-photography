CREATE DATABASE IF NOT EXISTS symfony
USE symfony

CREATE TABLE messages{
    id          int(255) auto_increment not null,
    name        varchar(50) not null,
    description text,
    contact     varchar(255),
    created_at  datetime DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_messages PRIMARY KEY(id)
}ENGINE=InnoDb;

CREATE TABLE images{
    id          int(255) auto_increment not null,
    title       varchar(255),
    description text,
    url         varchar(255),
    created_at  datetime DEFAULT CURRENT_TIMESTAMP
    CONSTRAINT pk_images PRIMARY KEY(id)
}ENGINE=InnoDb;