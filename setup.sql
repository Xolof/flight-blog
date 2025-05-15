DROP table IF EXISTS posts;
DROP table IF EXISTS comments;

CREATE TABLE posts (id INTEGER PRIMARY KEY, title TEXT, content TEXT, username TEXT, created_at TEXT, updated_at TEXT);

CREATE TABLE comments (
	id INTEGER PRIMARY KEY,
	post_id INTEGER,
	username TEXT,
	content TEXT,
	created_at TEXT,
	updated_at TEXT,
	FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

