-- scripts/schema.sqlite.sql
--
-- Definições da base local Miidle
 

-- idmap - Permite o mapeamento de ids entre as bases

CREATE TABLE idmap (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    context VARCHAR(255) NOT NULL DEFAULT 'SYS',
    id_legacy VARCHAR(2000) NULL,
    id_moodle VARCHAR(2000) NULL
);

CREATE TABLE log  (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    origin VARCHAR(255) NULL,
    destination VARCHAR(255) NULL,
    message VARCHAR(4000) NULL,
    tstamp TIMESTAMP NULL
);
 
CREATE INDEX "idx_idmap" ON "idmap" ("id");
CREATE INDEX "idx_log" ON "log" ("id");
