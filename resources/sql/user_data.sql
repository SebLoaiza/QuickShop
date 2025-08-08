CREATE DATABASE IF NOT EXISTS QuickShop_data;

USE QuickShop_data;

CREATE TABLE IF NOT EXISTS shopping_list (
    shopping_id     INT AUTO_INCREMENT PRIMARY KEY,
    image_url       VARCHAR(500)        NOT NULL,
    price           FLOAT               NOT NULL,
    name            VARCHAR(500)        NOT NULL,
    URL             VARCHAR(500)        NOT NULL,
    store           VARCHAR(25)         NOT NULL
);
