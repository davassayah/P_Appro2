CREATE TABLE t_section (
  idSection int(11) NOT NULL AUTO_INCREMENT,
  secName varchar(20) NOT NULL,
  PRIMARY KEY (idSection)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE t_teacher (
  idTeacher int(11) NOT NULL AUTO_INCREMENT,
  teaFirstname varchar(50) NOT NULL,
  teaName varchar(50) NOT NULL,
  teaGender char(1) NOT NULL,
  teaNickname varchar(50) NOT NULL,
  teaOrigine text NOT NULL,
  fkSection int(11) NOT NULL,
  PRIMARY KEY (idTeacher),
  FOREIGN KEY (fkSection) REFERENCES t_section(idSection)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE t_user (
  idUser int(11) NOT NULL AUTO_INCREMENT,
  useLogin varchar(20) NOT NULL,
  usePassword varchar(255) NOT NULL,
  useAdministrator int(2) NOT NULL,
  PRIMARY KEY (idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
