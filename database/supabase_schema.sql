-- =====================================================================
-- VoteSnap — Supabase PostgreSQL Database Schema
-- Copyright (c) 2026 Yahya-Studio
-- 
-- This SQL script can be executed directly inside Supabase SQL Editor
-- to initialize the complete database schema for VoteSnap.
-- =====================================================================

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. Password Reset Tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- 3. Sessions Table
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE CASCADE,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions(user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions(last_activity);

-- 4. Cache Tables
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- 5. Jobs Table
CREATE TABLE IF NOT EXISTS jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs(queue);

CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 6. Polls Table
CREATE TABLE IF NOT EXISTS polls (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    slug VARCHAR(64) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    question TEXT NOT NULL,
    description TEXT NULL,
    image_path VARCHAR(255) NULL,
    poll_type VARCHAR(32) NOT NULL DEFAULT 'single',
    max_choices SMALLINT NULL DEFAULT 1,
    start_at TIMESTAMP NULL,
    end_at TIMESTAMP NULL,
    visibility VARCHAR(32) NOT NULL DEFAULT 'public',
    show_results BOOLEAN NOT NULL DEFAULT true,
    results_visibility VARCHAR(32) NOT NULL DEFAULT 'always',
    allow_vote_change BOOLEAN NOT NULL DEFAULT false,
    randomize_options BOOLEAN NOT NULL DEFAULT false,
    is_closed_manually BOOLEAN NOT NULL DEFAULT false,
    closed_at TIMESTAMP NULL,
    votes_count INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS polls_visibility_created_at_idx ON polls(visibility, created_at);
CREATE INDEX IF NOT EXISTS polls_user_id_created_at_idx ON polls(user_id, created_at);

-- 7. Poll Options Table
CREATE TABLE IF NOT EXISTS poll_options (
    id BIGSERIAL PRIMARY KEY,
    poll_id BIGINT NOT NULL REFERENCES polls(id) ON DELETE CASCADE,
    option_text VARCHAR(255) NOT NULL,
    "order" INTEGER NOT NULL DEFAULT 0,
    votes_count INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS poll_options_poll_id_order_idx ON poll_options(poll_id, "order");

-- 8. Votes Table
CREATE TABLE IF NOT EXISTS votes (
    id BIGSERIAL PRIMARY KEY,
    poll_id BIGINT NOT NULL REFERENCES polls(id) ON DELETE CASCADE,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    voter_identifier VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    session_id VARCHAR(128) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT votes_poll_voter_unique UNIQUE (poll_id, voter_identifier)
);
CREATE INDEX IF NOT EXISTS votes_poll_id_user_id_idx ON votes(poll_id, user_id);

-- 9. Vote Answers Table
CREATE TABLE IF NOT EXISTS vote_answers (
    id BIGSERIAL PRIMARY KEY,
    vote_id BIGINT NOT NULL REFERENCES votes(id) ON DELETE CASCADE,
    poll_option_id BIGINT NOT NULL REFERENCES poll_options(id) ON DELETE CASCADE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT vote_answers_unique UNIQUE (vote_id, poll_option_id)
);
