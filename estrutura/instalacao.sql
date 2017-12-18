CREATE TABLE plugins.escolainsuficienciaespaco (escola INT NOT NULL,
                                                insuficiencia BOOL NOT NULL,
                                                PRIMARY KEY (escola));

CREATE TABLE plugins.etapacapacidade (etapa INT NOT NULL,
                                      minima INT NOT NULL,
                                      maxima INT NOT NULL,
                                      PRIMARY KEY (etapa));    
                                      
CREATE TABLE plugins.salacapacidade (sala INT NOT NULL,
                                     minima INT NOT NULL,
                                     maxima INT NOT NULL,
                                     PRIMARY KEY (sala));                                      
                                      
                                                                                  
--  
-- Inclusao dos menus (executar manualmente):
-- 
--                                                
-- insert into db_itensmenu values (nextval('db_itensmenu_id_item_seq'), 
--                                  'Escolas com insuficiência de espaço', 
--                                  'Escolas com insuficiência de espaço', 
--                                  'sec4_escolainsuficienciaespaco001.php', 
--                                  1, 
--                                  1, 
--                                  'Escolas com insuficiência de espaço', 
--                                  true);
--                                  
-- insert into db_menu values (9081, 
--                             currval('db_itensmenu_id_item_seq'),
--                             5,
--                             7159);
--                             
-- insert into db_itensmenu values (nextval('db_itensmenu_id_item_seq'),
--                                  'Etapa/Capacidade Matricula',
--                                  'Capacidade minima e maxima de matriculas para as turmas da etapa',
--                                  'edu1_etapacapacidade001.php',
--                                  1,
--                                  1,
--                                  'Capacidade minima e maxima de matriculas para as turmas da etapa',
--                                  true);
--                             
-- insert into db_menu values (1100808, 
--                             currval('db_itensmenu_id_item_seq'),
--                             7,
--                             7159);                             
--                                                               
--insert into db_syscampo (codcam,nomecam,rotulo,rotulorel) values (nextval('db_syscampo_codcam_seq'), 'etapa', 'Etapa','Etapa');
--insert into db_syscampo (codcam,nomecam,rotulo,rotulorel) values (nextval('db_syscampo_codcam_seq'), 'minima', 'Minima','Minima');
--insert into db_syscampo (codcam,nomecam,rotulo,rotulorel) values (nextval('db_syscampo_codcam_seq'), 'maxima', 'Maxima','Maxima');
                                  