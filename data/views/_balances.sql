CREATE VIEW work_balances AS

SELECT
	objects.id
AS 
	object_id,
	
	-- сколько дано денег
    objects.work
    
	-
	
    -- потрачено на зарплатников
    COALESCE(SUM(workers.salary)*TIMESTAMPDIFF(DAY, date_start, date_real_end), 0) 
   
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

LEFT JOIN payments
ON payments.object_id = objects.id

GROUP BY objects.id;






CREATE VIEW material_balances AS

SELECT
	objects.id
AS 
	object_id,
	
	-- сколько дано денег
    objects.material
    
	-
	
    -- потрачено на материалы
    COALESCE(SUM(purchases.cost), 0)
    

AS
	value
 

FROM 
	objects

LEFT JOIN purchases
ON purchases.object_id = objects.id

GROUP BY objects.id;
 

 
 

CREATE VIEW balances AS

SELECT
	objects.id  
AS 
	object_id,
	
	work_balances.value
	
	+
	
	material_balances.value

AS
	value
 

FROM 
	objects
	
LEFT JOIN
	work_balances
ON objects.id = work_balances.object_id

LEFT JOIN
	material_balances
ON objects.id = material_balances.object_id


GROUP BY objects.id;
 