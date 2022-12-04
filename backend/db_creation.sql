'''
This script drops an existing table (if it exists), creates it from scratch and populates it with data from a CSV file
'''

-- Drop existing table
DROP TABLE IF EXISTS tests2;

-- Create new table
CREATE TABLE tests2 (
	issue_id SERIAL PRIMARY KEY,
	user_name VARCHAR(20),
	user_id VARCHAR(20),
	timestamp VARCHAR(20),
	latitude FLOAT(4),
	longitude FLOAT(4),
	room VARCHAR(10),
	category VARCHAR(20),
	title VARCHAR(20),
	description VARCHAR(256),
	solution VARCHAR(256),
	urgency VARCHAR(20),
	difficulty VARCHAR(20),
	status VARCHAR(20),
	upvotes INT,
	has_upvoted BOOL
);

-- Copy contents from CSV file to table
COPY tests2 FROM 'C:\Users\Public\Documents\issue_db3.csv' WITH (FORMAT CSV, HEADER, ENCODING 'UTF8');

-- Update issue_id sequence number to sync it again
SELECT SETVAL('public."tests2_issue_id_seq"', COALESCE(MAX(issue_id), 1)) FROM tests2;
