-- 
-- Modify the resource table and add three new enum types.
-- 

ALTER TABLE resource MODIFY resource_type ENUM('Journal','Database','Platform','Aggregator','Ebook');
