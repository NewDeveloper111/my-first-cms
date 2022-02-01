/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/SQLTemplate.sql to edit this template
 */
/**
 * Author:  user
 * Created: Jan 31, 2022
 */
ALTER TABLE users MODIFY COLUMN id SMALLINT UNSIGNED auto_increment NOT NULL;
CREATE TABLE users_articles (
    user_id smallint unsigned NOT NULL,
    article_id smallint unsigned NOT NULL,
    PRIMARY KEY (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE
)
COMMENT='Таблица связи пользователей и статей'
ENGINE=InnoDB;