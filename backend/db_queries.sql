-- get all issues in database
SELECT * from issue_db;

-- get all issues in database where...
SELECT * from issue_db where user_name = 'Pavle';

-- get sum, count and average of upvotes per user
select user_name, SUM(upvotes) as uv_sum, COUNT(upvotes) as uv_count, AVG(upvotes) as uv_avg 
from issue_db
group by user_name 
order by uv_sum desc;

-- get total number of issues in database
select COUNT(*)
from issue_db;

-- get total number of users in database
select COUNT(DISTINCT(user_name))
from issue_db;

-- get total number of upvotes in database
select SUM(upvotes)
from issue_db;


-- check upvotes of a given issue
select upvotes
from tests 
where issue_id = 'i779310';

-- add one upvote to a given issue
UPDATE tests
SET upvotes = upvotes + 1
WHERE issue_id = 'i779310';


-- add a new issue with random values
INSERT INTO tests2(user_name)
VALUES ('la karen');

-- select issues that were not upvoted
select *
from issue_db
where has_upvoted = false;




-- Insert new issue with next id in sequence
INSERT INTO tests2(issue_id) VALUES (DEFAULT);

-- Delete entries with null vales
DELETE FROM issue_db WHERE issue_id IS NULL;
DELETE FROM issue_db WHERE user_name IS NULL;
