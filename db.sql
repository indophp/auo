CREATE TABLE tb_admin (
    aid CHAR(17) PRIMARY KEY,
    auser VARCHAR(100) NOT NULL,
    amail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    anama VARCHAR(150) NOT NULL,
    ha_id INT NOT NULL,
    last_login DATETIME DEFAULT NULL
);

CREATE TABLE tb_user (
    uid CHAR(17) PRIMARY KEY,
    uuser VARCHAR(100) NOT NULL,
    umail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    unama VARCHAR(150) NOT NULL,
    last_login DATETIME DEFAULT NULL
);

CREATE TABLE tb_member (
    mid CHAR(17) PRIMARY KEY,
    nim VARCHAR(100) NOT NULL,
    mmail VARCHAR(150) NOT NULL,
    sandi VARCHAR(255) NOT NULL,
    mnama VARCHAR(150) NOT NULL,
    last_login DATETIME DEFAULT NULL
);

CREATE TABLE tb_log (
    log_id CHAR(17) PRIMARY KEY,
    username VARCHAR(100),
    user_type VARCHAR(20),
    user_id CHAR(17),
    success TINYINT(1),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_admin_user ON tb_admin(auser);
CREATE INDEX idx_user_user ON tb_user(uuser);
CREATE INDEX idx_member_nim ON tb_member(nim);
