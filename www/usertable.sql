create database gradingsystem;
use gradingsystem;

create table course
(
	course_code varchar(10) not null primary key,
	course_name varchar(100) not null
)  ENGINE=INNODB;


create table user
(
	username varchar(20) not null primary key,
	password char(40) not null,
	email varchar(100) not null,
	privilege enum('student','instructor','administrator') not null
) ENGINE=INNODB;



create table enrollment
(
	en_id integer(10) not null primary key AUTO_INCREMENT,
	course_code  varchar(10) not null,
	username varchar(20) not null,
	Index (course_code),
	Foreign key(course_code) References course(course_code) on update cascade on delete cascade,
	Index (username),
	Foreign key(username) References user(username) on update cascade on delete cascade
)ENGINE=INNODB;

create table problem
(
	p_id integer(10) not null primary key AUTO_INCREMENT,
	p_num integer(10),
	p_name varchar(100) not null,
	course_code varchar(10) not null,
	tutorial integer(5) not null, 
	lang enum('Java','C++') not null,
	explanation varchar(500) not null,
	description text,
	input text,
	output text,
	sample_input text,
	sample_output text,
	hint text,
	Index (course_code),
	Foreign key(course_code) References course(course_code) on update cascade on delete cascade
)  ENGINE=INNODB;

create table testcase
(
	io_id integer(10) not null primary key AUTO_INCREMENT,
	p_id integer(10) not null,
	Index (p_id),
	Foreign key(p_id) References problem(p_id) on update cascade on delete cascade,
	
	inputs text not null,
	outputs text not null
) ENGINE=INNODB;

create table answer
(
	an_id integer(10) not null primary key AUTO_INCREMENT,
	username varchar(20) not null,
	p_id integer(10) not null,
	Index (username),
	Foreign key(username) References user(username) on update cascade on delete cascade,
	Index (p_id),
	Foreign key(p_id) References problem(p_id) on update cascade on delete cascade,
	
	filename varchar(255) not null,
	user_comments text,
	instruction text,
	upload_time datetime not null,
	no_attempts integer(10) not null,
	isLatest enum('true', 'false') not null,
	ex_status enum('Waiting', 'Compiling', 'Running', 'Interrupted','Complete') not null,
	status enum('Yes', 'No - Compilation Error', 'No - Runtime Error', 'No - Wrong Result', 'No - Time Limit Exceeded', 'No - Presentation Error')
) ENGINE=INNODB;

CREATE USER 'gsadmin'@'localhost' identified by 'ntu2011';

GRANT SELECT,INSERT,UPDATE,DELETE ON gradingsystem.* to 'gsadmin'@'localhost'; 

Insert into user(username, password, email, privilege) values ('admin', sha1('admin'), 'admin@ntu.edu.sg', 'administrator');
