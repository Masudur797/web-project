-- ==================== PORTFOLIO DATABASE SCHEMA ====================

-- Create Database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- ==================== CONTACT MESSAGES TABLE ====================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45),
    user_agent TEXT,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at),
    INDEX idx_is_read (is_read)
);

-- ==================== PROJECTS TABLE ====================
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    technologies TEXT NOT NULL,
    live_url VARCHAR(500),
    github_url VARCHAR(500),
    image_url VARCHAR(500),
    start_date DATE,
    end_date DATE,
    featured TINYINT(1) DEFAULT 0,
    status ENUM('completed', 'in-progress', 'planned') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_featured (featured),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- ==================== SKILLS TABLE ====================
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    skill_name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    proficiency INT DEFAULT 50,
    description TEXT,
    icon VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_proficiency (proficiency)
);

-- ==================== EXPERIENCE TABLE ====================
CREATE TABLE IF NOT EXISTS experience (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    description LONGTEXT,
    start_date DATE NOT NULL,
    end_date DATE,
    is_current TINYINT(1) DEFAULT 0,
    location VARCHAR(255),
    technologies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_current (is_current),
    INDEX idx_start_date (start_date)
);

-- ==================== EDUCATION TABLE ====================
CREATE TABLE IF NOT EXISTS education (
    id INT PRIMARY KEY AUTO_INCREMENT,
    institution VARCHAR(255) NOT NULL,
    degree VARCHAR(255) NOT NULL,
    field_of_study VARCHAR(255),
    grade VARCHAR(10),
    start_date DATE,
    end_date DATE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_institution (institution)
);

-- ==================== BLOG POSTS TABLE ====================
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt VARCHAR(500),
    author VARCHAR(255),
    category VARCHAR(100),
    image_url VARCHAR(500),
    published TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    INDEX idx_published (published),
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    FULLTEXT INDEX ft_title_content (title, content)
);

-- ==================== TESTIMONIALS TABLE ====================
CREATE TABLE IF NOT EXISTS testimonials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    company VARCHAR(255),
    message LONGTEXT NOT NULL,
    rating INT,
    image_url VARCHAR(500),
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_featured (featured),
    INDEX idx_rating (rating)
);

-- ==================== ADMIN USERS TABLE ====================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- ==================== ANALYTICS TABLE ====================
CREATE TABLE IF NOT EXISTS page_analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(255),
    views INT DEFAULT 0,
    unique_visitors INT DEFAULT 0,
    bounce_rate DECIMAL(5, 2),
    avg_time_on_page INT,
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_page_name (page_name),
    INDEX idx_date (date)
);

-- ==================== NEWSLETTER SUBSCRIBERS TABLE ====================
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    subscribed TINYINT(1) DEFAULT 1,
    confirmation_token VARCHAR(255),
    confirmed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_subscribed (subscribed)
);

-- ==================== SAMPLE DATA ====================

-- Insert sample skills
INSERT INTO skills (skill_name, category, proficiency, description) VALUES
('HTML5', 'Frontend', 95, 'Semantic HTML markup'),
('CSS3', 'Frontend', 90, 'Responsive styling and animations'),
('JavaScript', 'Frontend', 85, 'ES6+, DOM manipulation, APIs'),
('React', 'Frontend', 80, 'Components, Hooks, State management'),
('Node.js', 'Backend', 80, 'Express.js, RESTful APIs'),
('PHP', 'Backend', 75, 'Server-side programming'),
('MySQL', 'Database', 80, 'Database design and queries'),
('MongoDB', 'Database', 75, 'NoSQL databases'),
('Git', 'Tools', 90, 'Version control'),
('Docker', 'Tools', 60, 'Containerization');

-- Insert sample experience
INSERT INTO experience (company, position, start_date, end_date, is_current, location, technologies) VALUES
('Tech Company', 'Full Stack Developer', '2022-01-15', NULL, 1, 'Remote', 'React, Node.js, MongoDB'),
('Previous Company', 'Frontend Developer', '2020-06-01', '2021-12-31', 0, 'City Name', 'React, JavaScript, CSS');

-- Insert sample education
INSERT INTO education (institution, degree, field_of_study, grade, start_date, end_date) VALUES
('University Name', 'Bachelor', 'Computer Science', 'A+', '2016-09-01', '2020-05-31');

-- ==================== CREATE VIEWS ====================

-- View for featured projects
CREATE VIEW featured_projects AS
SELECT * FROM projects
WHERE featured = 1 AND status = 'completed'
ORDER BY created_at DESC;

-- View for recent blog posts
CREATE VIEW recent_blog_posts AS
SELECT * FROM blog_posts
WHERE published = 1
ORDER BY published_at DESC
LIMIT 10;

-- ==================== INDEXES FOR PERFORMANCE ====================

-- Additional indexes for common queries
CREATE INDEX idx_contact_date_range ON contact_messages(created_at);
CREATE INDEX idx_project_featured_status ON projects(featured, status);
CREATE INDEX idx_blog_category_published ON blog_posts(category, published);
CREATE INDEX idx_testimonial_rating ON testimonials(rating);

-- ==================== USERS MANAGEMENT ====================

-- Create default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, email, password_hash, role) VALUES
('admin', 'admin@portfolio.local', SHA2('admin123', 256), 'admin');

-- ==================== NOTES ====================
-- 1. Always use prepared statements to prevent SQL injection
-- 2. Hash passwords using secure algorithms (bcrypt recommended)
-- 3. Backup database regularly
-- 4. Use HTTPS for all communication
-- 5. Implement proper access controls
-- 6. Keep database user privileges minimal (create separate users for different roles)
-- 7. Regular security audits
-- 8. Monitor for suspicious activities

-- ==================== SAMPLE QUERIES ====================

-- Get all unread contact messages
-- SELECT * FROM contact_messages WHERE is_read = 0 ORDER BY created_at DESC;

-- Get featured projects
-- SELECT * FROM featured_projects;

-- Get user statistics
-- SELECT COUNT(*) as total_messages, COUNT(DISTINCT email) as unique_visitors FROM contact_messages;

-- Get analytics
-- SELECT * FROM page_analytics ORDER BY date DESC LIMIT 30;