/* * * * * * * * * * * * * * *
 * Created by: Nathan Healea.
 * Project: WordSalad
 * File:  wordsalad_database_scheme.sql
 * Date: 2/4/16 
 * Time: 11:57 AM
 * Description
 * * * * * * * * * * * * * * */

/* * * * * * * * * * * * * * *
 * Table: ws_language
 * Description: hold all the information for languages used by WordSalad.
 * * * * * * * * * * * * * * */
CREATE TABLE ws_language(
  id INT NOT NULL PRIMARY KEY,
  language TEXT NOT NULL,
  code varchar(4) NOT NULL,
  alphabet VARCHAR(255) NOT NULL,
  length INT NOT NULL,
  expression TEXT NOT NULL,
  punctuation VARCHAR(255) NOT NULL,
  threshold DOUBLE NOT NULL,
  percentage DOUBLE NOT NULL
);

/* * * * * * * * * * * * * * *
 * Table: ws_language_sentences
 * Description: Holds sentences for language used to determine threshold and percentage.
 * * * * * * * * * * * * * * */
CREATE TABLE ws_language_sentence(
  id INT NOT NULL PRIMARY KEY,
  language_id INT NOT NULL,
  sentence TEXT NOT NULL,
  CONSTRAINT FK_WLS_LId_WL_Id FOREIGN KEY (language_id) REFERENCES ws_language(id)
    on DELETE CASCADE on UPDATE CASCADE
);

/* * * * * * * * * * * * * * *
 * Table: ws_matrix_info
 * Description: Hold the header information for a language matrix
 * * * * * * * * * * * * * * */
CREATE TABLE ws_matrix_info(
  id  INT NOT NULL PRIMARY KEY,
  language_id INT NOT NULL,
  CONSTRAINT FK_WMI_LId_WL_Id FOREIGN KEY (language_id) REFERENCES ws_language(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);
/* * * * * * * * * * * * * * *
 * Table: ws_matrix
 * Description: Hold the probability score of two letters showing up next to each other.
 * * * * * * * * * * * * * * */
CREATE  TABLE ws_matrix(
  id INT NOT NULL PRIMARY KEY,
  matrix_id INT NOT NULL,
  row_num INT NOT NULL,
  row_char VARCHAR(1) NOT NULL,
  colum_num INT NOT NULL,
  colum_char VARCHAR(1) NOT NULL,
  score DOUBLE NOT NULL,
  CONSTRAINT FK_WM_MatId_WMI_Id FOREIGN KEY (matrix_id) REFERENCES ws_matrix_info(id)
    ON DELETE CASCADE ON UPDATE CASCADE
);




/* * * * * * * * * * * * * * *
 * Remove table statements
 * * * * * * * * * * * * * * */
DROP TABLE ws_language;
DROP TABLE ws_language_sentence;
DROP TABLE ws_matrix_info;
DROP TABLE ws_matrix;

