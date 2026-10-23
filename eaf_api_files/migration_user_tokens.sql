-- ============================================================
-- EAF Connect Mobile App - Database Migration
-- Run this SQL on the live database: u594406910_eafv2
-- ============================================================

-- Create API token table for mobile authentication
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `token_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code`    VARCHAR(50) NOT NULL,
  `token`        VARCHAR(64) NOT NULL COMMENT 'SHA-256 hashed token',
  `device_info`  VARCHAR(255) DEFAULT '' COMMENT 'Device name/model from mobile app',
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at`   DATETIME NOT NULL,
  `last_used_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `idx_token` (`token`),
  KEY `idx_user_code` (`user_code`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_user_tokens_user` FOREIGN KEY (`user_code`)
    REFERENCES `users` (`user_code`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Mobile API authentication tokens';

-- Optional: auto-cleanup expired tokens (run manually or via cron)
-- DELETE FROM user_tokens WHERE expires_at < NOW();
