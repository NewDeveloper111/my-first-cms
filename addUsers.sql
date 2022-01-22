/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/SQLTemplate.sql to edit this template
 */
/**
 * Author:  user
 * Created: Jan 18, 2022
 */
CREATE TABLE cms.users (
	id smallint primary key auto_increment,
	login varchar(20) not null,
	passw varchar(20) not null,
	active bool default(true) not null
);