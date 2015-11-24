CREATE VIEW managers AS

SELECT
	objects_workers.object_id  
AS 
	object_id,
	
	workers.id
AS
	worker_id
	
FROM
	objects_workers

LEFT JOIN workers ON workers.id = objects_workers.worker_id

LEFT JOIN professions ON professions.id = workers.profession_id

WHERE professions.name = "manager";