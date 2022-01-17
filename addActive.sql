/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/SQLTemplate.sql to edit this template
 */
/**
 * Author:  user
 * Created: Jan 16, 2022
 */
USE cms;
ALTER TABLE articles ADD COLUMN active TINYINT DEFAULT 1 AFTER content;