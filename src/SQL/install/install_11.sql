<<<<<<< HEAD
ALTER TABLE 'group_lesson' ADD COLUMN 'mail' INTEGER DEFAULT 0;
=======
CREATE TABLE IF NOT EXISTS "exported_events" ("user_id" integer NOT NULL,"lesson_id" integer NOT NULL,"calendar_id" text NOT NULL,"event_id" text NOT NULL,FOREIGN KEY ("user_id") REFERENCES "user" ("id"),FOREIGN KEY ("lesson_id") REFERENCES "lesson" ("id"));
>>>>>>> origin/master
