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
 