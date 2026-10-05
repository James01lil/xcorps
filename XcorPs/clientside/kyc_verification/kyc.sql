CREATE TABLE kyc_submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  full_name VARCHAR(255),
  dob DATE,
  id_front VARCHAR(255),
  id_back VARCHAR(255),
  selfie VARCHAR(255),
  status VARCHAR(20) DEFAULT 'Pending',
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);