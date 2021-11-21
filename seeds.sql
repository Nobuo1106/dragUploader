create table file (
    id AUTO_INCREMENT(11) AUTO_INCREMENT NOT NULL, 
    name VARCHAR(255),
    org_name VARCHAR(255) UNIQUE,
    path VARCHAR(255),
    PRIMARY KEY (id)
);