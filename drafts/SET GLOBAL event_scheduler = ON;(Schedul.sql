SET GLOBAL event_scheduler = ON;(Scheduler is ON)

CREATE EVENT IF NOT EXISTS clean_duplicate_logs
ON SCHEDULE EVERY 5 DAY
STARTS CURRENT_TIMESTAMP
DO
  DELETE l1 
  FROM activity_logs l1
  INNER JOIN activity_logs l2 
  ON l1.action = l2.action 
     AND l1.idFiscal = l2.idFiscal 
     AND l1.details = l2.details 
     AND l1.mois = l2.mois 
     AND l1.annee = l2.annee
  WHERE l1.id > l2.id;

  SHOW EVENTS(To see all events)

  DROP EVENT clean_duplicate_logs;(To delete the event)


  ALTER EVENT clean_duplicate_logs DISABLE;(To disable it temporarily)