-- Add admin role to users table
ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0;

-- Set nhaterik as admin
UPDATE `users` SET `is_admin` = 1 WHERE `username` = 'nhaterik';
