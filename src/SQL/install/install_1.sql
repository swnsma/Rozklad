DROP TABLE IF EXISTS "group";
CREATE TABLE "group" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text NOT NULL,
  "teacher_id" integer NOT NULL,
  FOREIGN KEY ("id") REFERENCES "student-group" ("group_id") ON DELETE SET NULL ON UPDATE CASCADE
);


DROP TABLE IF EXISTS "group_lesson";
CREATE TABLE "group_lesson" (
  "group_id" integer NOT NULL,
  "lesson_id" integer NOT NULL,
  PRIMARY KEY ("group_id", "lesson_id")
);


DROP TABLE IF EXISTS "lesson";
CREATE TABLE "lesson" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "date" text NOT NULL,
  "topic" text NOT NULL,
  "description" text NOT NULL,
  FOREIGN KEY ("id") REFERENCES "group_lesson" ("lesson_id") ON DELETE SET NULL ON UPDATE CASCADE
);


DROP TABLE IF EXISTS "role";
CREATE TABLE "role" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "title" integer NOT NULL,
  FOREIGN KEY ("id") REFERENCES "user" ("role_id") ON DELETE SET NULL ON UPDATE CASCADE
);


DROP TABLE IF EXISTS "student_group";
CREATE TABLE "student_group" (
  "student_id" integer NOT NULL,
  "group_id" integer NOT NULL,
  PRIMARY KEY ("student_id", "group_id")
);


DROP TABLE IF EXISTS "user";
CREATE TABLE "user" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" text NOT NULL,
  "surname" text NOT NULL,
  "email" text NOT NULL,
  "phone" integer NOT NULL,
  "password" text NULL,
  "activated" integer NOT NULL DEFAULT '0',
  "role_id" integer NOT NULL DEFAULT '0',
  "open_id_g" text NOT NULL,
  "open_id_fb" text NOT NULL,
  FOREIGN KEY ("id") REFERENCES "student-group" ("student_id") ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY ("id") REFERENCES "group" ("teacher_id") ON DELETE CASCADE ON UPDATE CASCADE
);