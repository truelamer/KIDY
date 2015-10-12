CREATE VIEW balances AS

SELECT
	objects.id  
AS 
	object_id,
	
	-- сколько дано денег
    (objects.material+objects.work)
    
	-
	
    -- потрачено на зарплатников
    COALESCE(SUM(workers.salary)*TIMESTAMPDIFF(DAY, date_start, date_real_end), 0) 
   
	-
	
    -- потрачено на материалы
    COALESCE(SUM(purchases.cost), 0)
    
	-
	
    -- потрачено на работников, которые получают зарплату сдельно
    COALESCE(SUM(payments.amount), 0)
AS
	value
 

FROM 
	objects
	
LEFT JOIN
	objects_workers
ON objects.id = objects_workers.object_id

LEFT JOIN workers ON workers.id = objects_workers.worker_id

LEFT JOIN purchases
ON purchases.object_id = objects.id

LEFT JOIN payments
ON payments.object_id = objects.id

GROUP BY objects.id;
 