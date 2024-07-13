

# University Access Management System

## Introduction
This project is a PHP web application designed for managing access to a university using QR codes. It supports different types of users, including students, employees, agents, and visitors, providing a secure and efficient way to control access.

## Features
- User authentication for admins, agents, students, employees, and visitors.
- QR code generation for access control.
- Access logging and management.
- Database management using phpMyAdmin.

## Installation

### Prerequisites
- PHP 8.2.4 or higher
- MariaDB 10.4.28 or higher
- phpMyAdmin 5.2.1 or higher

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/embarki34/BBA_hacktivia.git
   ```

2. Navigate to the project directory:
   ```bash
   cd university-access-management
   ```

3. Set up the database:
   - Import the provided SQL dump into your MariaDB database using phpMyAdmin or the command line:
     ```bash
     mysql -u yourusername -p yourpassword codemaster < path/to/sql_dump.sql
     ```

4. Configure the database connection:
   - Update the database connection details in the configuration file (e.g., `config.php`).

5. Start your local server (e.g., using XAMPP or MAMP).

6. Open your web browser and navigate to the project URL (e.g., `http://localhost/university-access-management`).

## Usage

### Admin
- Admins can log in using the default credentials (`username: admin, password: admin`).
- Admins can manage agents, students, employees, and visitors.

### Agent
- Agents can log in and manage access requests.

### Student/Employee/Visitor
- Students, employees, and visitors can log in using their credentials and view their access status.

## Database Schema
The database includes the following tables:
- `access`
- `admin`
- `agent`
- `employee`
- `student`
- `visitor`

Refer to the SQL dump for the complete schema.

## Contributing

1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature-branch
   ```
3. Make your changes.
4. Commit your changes:
   ```bash
   git commit -m 'Add some feature'
   ```
5. Push to the branch:
   ```bash
   git push origin feature-branch
   ```
6. Open a pull request.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments
- Thanks to all the contributors and supporters.
- Inspired by various access management systems and security protocols.

