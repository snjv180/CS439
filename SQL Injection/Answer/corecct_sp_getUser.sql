DELIMITER //
CREATE PROCEDURE sp_getUser( IN inputID INT, OUT resID INT, OUT uname VARCHAR(40))
   BEGIN
   SELECT ID,username INTO resID,uname  FROM member where ID=inputID;
   END //
DELIMITER ;