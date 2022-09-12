# microblog-veronica
 


```sql

CREATE TABLE usuarios ( id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT, nome VARCHAR(45) NOT NULL, email VARCHAR(45) UNIQUE NOT NULL, senha VARCHAR(255) NOT NULL, tipo ENUM('admin', 'editor') NOT NULL );


CREATE TABLE categorias ( id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT, nome VARCHAR(45) NOT NULL );


CREATE TABLE noticias ( id MEDIUMINT NOT NULL PRIMARY KEY AUTO_INCREMENT, data DATETIME NOT NULL, titulo VARCHAR(150) NOT NULL, texto TEXT NOT NULL, resumo TINYTEXT NOT NULL, imagem VARCHAR(45) NOT NULL, destaque ENUM('sim', 'nao') NOT NULL, usuarios_id SMALLINT NULL, categorias_id TINYINT NULL );


ALTER TABLE noticias ADD CONSTRAINT fk_usuarios_noticias FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE categorias ADD CONSTRAINT fk_categorias_noticias FOREIGN KEY (categorias_id) REFERENCES categorias(id) ON DELETE SET NULL ON UPDATE NO ACTION;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES (NULL, 'Veronica Silva', 'veronica@veronica.com', '123456', 'admin');