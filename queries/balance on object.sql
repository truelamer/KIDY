SELECT
	object.id as "Номер объекта",
	
	#сколько дано денег
    (object.material+object.work)
    -
    #потрачено на зарплатников
    COALESCE(SUM(workers.salary)*TIMESTAMPDIFF(DAY, date_start, date_real_end), 0) 
    -
    #потрачено на материалы
    COALESCE(SUM(purchases.cost), 0)
    -
    #потрачено на работников, которые получают зарплату сдельно
    COALESCE(SUM(payments.amount), 0)
    
    AS "Баланс"
FROM 
	objects as object
    
LEFT JOIN 
(
SELECT * FROM purchases GROUP BY object_id
) AS purchases
ON purchases.object_id = object.id

LEFT JOIN 
(
SELECT * FROM payments GROUP BY object_id
) AS payments
ON payments.object_id = object.id

LEFT JOIN
(
SELECT * FROM objects_workers LEFT JOIN workers ON workers.id = objects_workers.worker_id GROUP BY object_id
) AS workers
ON workers.object_id = object.id

WHERE object.id = 1
group by workers.id