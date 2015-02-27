DROP TABLE IF EXISTS "comment";
CREATE TABLE IF NOT EXISTS
"comment"
(
"id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
"user_id" integer NOT NULL,
"lesson_id" integer NOT NULL ,
"date" text NOT NULL ,
"text" text NULL ,
"pid" integer NULL,
"status" integer NULL,
FOREIGN KEY ("user_id") REFERENCES "user" ("id"),
FOREIGN KEY ("lesson_id") REFERENCES "lesson" ("id")
);