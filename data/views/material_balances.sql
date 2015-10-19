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