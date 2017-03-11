create table users
(
	id int auto_increment primary key,
	nickname varchar(30),
	full_name varchar(50),
	email varchar(100),
	password varchar(32),
	secret_question varchar(200),
	secret_answer varchar(50),
	last_hit datetime,
	timezone varchar(50),
	inserted datetime,
	updated datetime,
	updated_by int,
	deleted datetime,
	deleted_by int,
	fav_bible varchar(20),
	verification_code varchar(36),
	remote_addr varchar(50),
	topics_per_page int,
	posts_per_page int,
	messages_per_page int,

	-- may be someday they will become normal =]
	country varchar(50),
	language varchar(50)
);

create table fav_verses
(
	id int auto_increment primary key,
	user_id int,
	verseID varchar(16),
	b_code varchar(20),
	inserted datetime,
	sort int
);

create table tweeted_verses
(
	id int auto_increment primary key,
	verseID varchar(16),
	times_tweeted int
);