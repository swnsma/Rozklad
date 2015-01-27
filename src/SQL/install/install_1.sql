CREATE TABLE IF NOT EXISTS "group" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text NULL,
  "teacher_id" integer NULL,
  FOREIGN KEY ("id") REFERENCES "student-group" ("group_id") ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS"group_lesson" (
  "group_id" integer NOT NULL,
  "lesson_id" integer NOT NULL,
  PRIMARY KEY ("group_id", "lesson_id")
);


CREATE TABLE IF NOT EXISTS"lesson" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "date" text NULL,
  "title" text NULL,
  "description" text NULL,
  "start" text NULL,
  "end" text NULL,
  FOREIGN KEY ("id") REFERENCES "group_lesson" ("lesson_id") ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS "role" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "title" integer NOT NULL,
  FOREIGN KEY ("id") REFERENCES "user" ("role_id") ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS "student_group" (
  "student_id" integer NOT NULL,
  "group_id" integer NOT NULL,
  PRIMARY KEY ("student_id", "group_id")
);


CREATE TABLE IF NOT EXISTS "user" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text NULL,
  "surname" text NULL,
  "email" text NULL,
  "phone" integer NULL,
  "role_id" integer NULL DEFAULT '1',
  "gm_id" text NULL,
  "fb_id" text NULL,
  "key" text NULL,
  FOREIGN KEY ("id") REFERENCES "group" ("teacher_id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("id") REFERENCES "student-group" ("student_id") ON DELETE SET NULL ON UPDATE CASCADE
);
INSERT INTO "role" ("id", "title") VALUES (1,	student);
INSERT INTO "role" ("id", "title") VALUES (2,	teacher);
