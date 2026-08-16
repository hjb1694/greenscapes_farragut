CREATE TABLE contact_inquiries (
    id int AUTO_INCREMENT PRIMARY KEY,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    can_text boolean NOT NULL DEFAULT 0,
    email varchar(100) NOT NULL,
    message_body text
)