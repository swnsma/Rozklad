INSERT INTO "groups" ( "name", "teacher_id") VALUES (	'Advance',	3);
INSERT INTO "group_lesson" ("group_id", "lesson_id") VALUES (1,	1);
INSERT INTO "lesson" ("date", "title", "description", "start", "end") VALUES (	'28.01.2015',	'HTML',	'Знакомство с HTML 5.0',	'14:00',	'15:00');
INSERT INTO "student_group" ("student_id", "group_id") VALUES (1,	1);
INSERT INTO "student_group" ("student_id", "group_id") VALUES (2,	1);
INSERT INTO "user" ( "name", "surname", "email", "phone", "role_id", "gm_id", "fb_id", "key") VALUES (	'Вася',	'Пупкин',	'pupckin@gmail.com',	38901234,	1,	NULL,	NULL,	NULL);
INSERT INTO "user" ( "name", "surname", "email", "phone", "role_id", "gm_id", "fb_id", "key") VALUES (	'Иван',	'Петров',	'petrov@gmail.com',	395345,	1,	NULL,	NULL,	NULL);
INSERT INTO "user" ( "name", "surname", "email", "phone", "role_id", "gm_id", "fb_id", "key") VALUES (	'Иван',	'Васильев',	'vasja@gmail.com',	34343245,	2,	NULL,	NULL,	NULL);