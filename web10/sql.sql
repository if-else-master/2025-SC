CREATE DATABASE web08;

USE web08;

CREATE TABLE companies (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  address varchar(255) DEFAULT NULL,
  phone varchar(20) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  owner_name varchar(100) DEFAULT NULL,
  status enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (id)
)

CREATE TABLE products (
  id int(11) NOT NULL AUTO_INCREMENT,
  company_id int(11) DEFAULT NULL,
  name varchar(255) NOT NULL,
  name_en varchar(255) NOT NULL,
  gtin varchar(13) NOT NULL,
  description text DEFAULT NULL,
  description_en text DEFAULT NULL,
  image_path varchar(255) DEFAULT 'default.jpg',
  status enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (id),
  KEY company_id (company_id),
  CONSTRAINT products_ibfk_1 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE
) 
