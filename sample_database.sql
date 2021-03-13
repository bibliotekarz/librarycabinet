CREATE TABLE IF NOT EXISTS "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "date_insertion" DATETIME,
    "title" TEXT,
    "access_code" TEXT,
    "user_id" TEXT,
    "box_nr" INTEGER,
    "unit_id" INTEGER
);
CREATE TABLE IF NOT EXISTS "librarian" (
    "librarian_id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "librarian_name" TEXT NOT NULL UNIQUE,
    "librarian_pass" TEXT,
    "librarian_level" INTEGER
);
CREATE TABLE IF NOT EXISTS "unit" (
    "unit_id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "unit_name" TEXT,
    "unit_address" TEXT,
    "number_columns" INTEGER,
    "number_box" INTEGER
);
INSERT INTO "user" (
        "date_insertion",
        "title",
        "access_code",
        "user_id",
        "box_nr",
        "unit_id"
    )
VALUES (
        "2021-01-14 10:11:23",
        "Pan Tadeusz czyli ostatni zajazd na Litwie",
        363636,
        0123456,
        10,
        1
    );
INSERT INTO "unit" (
        "unit_name",
        "unit_address",
        "number_columns",
        "number_box"
    )
VALUES (
        "Biblioteka dla Dzieci i Młodzieży nr. 66",
        "Warszawa ul. Błotna 4",
        3,
        12
    );
INSERT INTO "librarian" (
        "librarian_name",
        "librarian_pass",
        "librarian_level"
    )
VALUES (
        "admin",
        "21232f297a57a5a743894a0e4a801fc3",
        0
    );