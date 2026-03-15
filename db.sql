CREATE TABLE tb_admin (
    aid INT PRIMARY KEY AUTO_INCREMENT,
    auser VARCHAR(100) NOT NULL,
    amail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    anama VARCHAR(150) NOT NULL,
    ha_id INT NOT NULL,
    last_login DATETIME NULL
);
CREATE TABLE tb_user (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    uuser VARCHAR(100) NOT NULL,
    umail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    unama VARCHAR(150) NOT NULL,
    last_login DATETIME DEFAULT NULL
);
CREATE TABLE tb_member (
    mid INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(100) NOT NULL,
    mmail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    mnama VARCHAR(150) NOT NULL,
    last_login DATETIME DEFAULT NULL
);
CREATE TABLE tb_log (
    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    user_type VARCHAR(20),
    user_id INT,
    success TINYINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME
);
