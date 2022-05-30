
CREATE TABLE `Usuario` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) NOT NULL,
  `Email` text NOT NULL,
  `Senha` text NOT NULL,
  `Hierarquia` varchar(10) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `Corretor` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Id_Usuario` int NOT NULL,
  `Nome_Corretor` varchar(50) NOT NULL,
  `Email_Corretor` text NOT NULL,
  `Creci` varchar(20) NOT NULL,
  `Whatsapp` varchar(20) NOT NULL,
  `Foto_Corretor` text,
  PRIMARY KEY (`Id`),
  KEY `Id_Usuario` (`Id_Usuario`),
  CONSTRAINT `Corretor_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `Usuario` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `Imovel` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Corretor_Id` int NOT NULL,
  `Usuario_ID` int NOT NULL,
  `Titulo` text NOT NULL,
  `Titulo_Slug` text NOT NULL,
  `Imagem_Capa` text NOT NULL,
  `Descricao` text NOT NULL,
  `Situacao` varchar(30) NOT NULL,
  `Tamanho` int NOT NULL,
  `Preco` varchar(50) NOT NULL,
  `Numero_Quartos` int NOT NULL,
  `Numero_Banheiros` int NOT NULL,
  `Numero_Vagas` int NOT NULL,
  `Numero_Suites` int NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Corretor_Id` (`Corretor_Id`),
  CONSTRAINT `Imovel_ibfk_1` FOREIGN KEY (`Corretor_Id`) REFERENCES `Corretor` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `Imovel_ibfk_2` FOREIGN KEY (`Usuario_ID`) REFERENCES `Usuario` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `Imagem` (
  `Id_Imagem` int NOT NULL AUTO_INCREMENT,
  `Id_Imovel` int NOT NULL,
  `Caminho_Imagem` text NOT NULL,
  PRIMARY KEY (`Id_Imagem`),
  KEY `Id_Imovel` (`Id_Imovel`),
  CONSTRAINT `Imagem_ibfk_1` FOREIGN KEY (`Id_Imovel`) REFERENCES `Imovel` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `Imovel_Lead` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Id_Imovel` int NOT NULL,
  `Data_Lead` varchar(30) NOT NULL,
  `Horario_Lead` varchar(30) NOT NULL,
  `Email_Cliente` text NOT NULL,
  `Telefone_Cliente` varchar(30) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id_Imovel` (`Id_Imovel`),
  CONSTRAINT `Imovel_Lead_ibfk_1` FOREIGN KEY (`Id_Imovel`) REFERENCES `Imovel` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

