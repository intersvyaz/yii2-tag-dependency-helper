CREATE TABLE "table"
(
  "id"         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title"      TEXT                              NOT NULL,
  "created_at" INTEGER                           NOT NULL DEFAULT 0,
  "updated_at" INTEGER                           NOT NULL DEFAULT 0
);

INSERT INTO "main"."table" VALUES (1, 'row1', 1340529410, 1340529410);
INSERT INTO "main"."table" VALUES (2, 'row2', 1340529305, 1340529305);
INSERT INTO "main"."table" VALUES (3, 'row3', 1340529200, 1340529200);

CREATE TABLE "table2"
(
  "id1"        INTEGER NOT NULL,
  "id2"        INTEGER NOT NULL,
  "title"      TEXT    NOT NULL,
  "created_at" INTEGER NOT NULL DEFAULT 0,
  "updated_at" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id1", "id2")
);

INSERT INTO "main"."table2" VALUES (1, 1, 'row1', 1340529410, 1340529410);
INSERT INTO "main"."table2" VALUES (2, 2, 'row2', 1340529305, 1340529305);
INSERT INTO "main"."table2" VALUES (3, 3, 'row3', 1340529200, 1340529200);
