/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/SQLTemplate.sql to edit this template
 */
/**
 * Author:  user
 * Created: Jan 23, 2022
 */
CREATE TABLE subcategories (id SMALLINT PRIMARY KEY AUTO_INCREMENT,
                            name VARCHAR(100) NOT NULL,
                            categoryId SMALLINT UNSIGNED DEFAULT NULL,
                            FOREIGN KEY (categoryId) REFERENCES categories (id)
                            ON DELETE SET NULL
                            ) ENGINE=INNODB;
//ALTER TABLE subcategories DISABLE KEYS;
//SET GLOBAL FOREIGN_KEY_CHECKS=0;
//SET GLOBAL FOREIGN_KEY_CHECKS=1;
ALTER TABLE articles ADD COLUMN subcategoryId SMALLINT DEFAULT NULL AFTER categoryId;
ALTER TABLE articles ADD FOREIGN KEY (subcategoryId) REFERENCES subcategories (id) ON DELETE SET NULL;
ALTER TABLE subcategories ADD FOREIGN KEY (categoryId) REFERENCES categories (id) ON DELETE SET NULL;
ALTER TABLE articles ADD FOREIGN KEY (categoryId) REFERENCES categories (id) ON DELETE SET NULL;
ALTER TABLE articles MODIFY COLUMN categoryId smallint unsigned NULL;