CREATE VIEW work_balances AS

SELECT
	objects.id
AS 
	object_id,
	
	-- ������� ���� �����
    objects.work
    
	-
	
    -- ��������� �� ������������
    COALESCE(SUM(workers.salary)*TIMESTAMPDIFF(DAY, date_start, date_real_end), 0) 
   
	-
	
    -- ��������� �� ����������, ������� �������� �������� �������
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