<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/marshmll/purrfect-match">
    <img src="https://raw.githubusercontent.com/marshmll/purrfect-match/refs/heads/main/public/images/icons/paw_white.svg" alt="Logo" width="150" height="auto">
  </a>

  <h3 align="center">Purrfect Match Web App</h3>

  <p align="center">
    A web application developed for <b>Crazy Cat Gang</b>, an NGO in Curitiba, Brazil, focused on cat rescue and adoption. The platform optimizes adoption processes, centralizes data management, and enhances user experience through role-based access, customizable features, and streamlined communication.
    <br />
  </p>
</div>

## Key Features
- **Centralized Operations**: Efficient management of adoptions and rescue requests.
- **Secure Data Persistence**: Maintains comprehensive records with role-based access control (JWT).
- **Personalized User Experience**: Filters for preferred cat traits and profile customization.
- **Real-Time Communication**: Integrated chat for inquiries and support.

## Technology Stack
- **Front-End**: HTML5, CSS3, JavaScript (ES14)
- **Back-End**: PHP 8, MySQL 8
- **Infrastructure**: Dockerized with Apache server
- **Authentication**: Secure login with JWT

## Database Information
- **Database Management System**: MySQL 8 (via Docker).
- **Features**:
  - Supports efficient and secure relational data storage.
  - Tables for users, cats, adoptions, rescue requests, chats, and preferences.
  - Advanced indexing for fast query execution.
- **Containerization**: 
  - MySQL container setup using `mysql/mysql-server:latest` image.
  - Mounted scripts for database initialization and configuration.
  - Access ports: `3307` for external connections.

## Infrastructure Highlights
- Docker Compose setup with separate containers for web and database services.
- Organized directory structure for scalability and maintainability.
- Public GitHub repository for codebase: [Purrfect Match](https://github.com/marshmll/purrfect-match).

## Data Models
- Comprehensive entity-relationship model tailored to adoption/rescue workflows.
- Features include user preferences, messaging, and detailed cat records (e.g., health, vaccinations).

## API Design
- RESTful API endpoints for adoption management, user profiles, and real-time chat.
- Secure operations with password hashing (HMAC-SHA256) and role validation.

## Front-End Design
- Minimalist and intuitive user interface.
- Dynamic elements for enhanced usability and engagement.

## Deployment
Deployed via Docker containers ensuring portability and ease of configuration.

## Project Team
<p align="center">
  <a href="https://github.com/marshmll">
    <img src="https://github.com/marshmll.png?size=100" alt="Renan" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Renan da Silva Oliveira Andrade</b>
  </a><br>renan.silva3@pucpr.edu.br
</p>

<p align="center">
  <a href="https://github.com/Ricardo-LK">
    <img src="https://github.com/Ricardo-LK.png?size=100" alt="Ricardo" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Ricardo Lucas Kucek</b>
  </a><br>ricardo.kucek@pucpr.edu.br
</p>

<p align="center">
  <a href="https://github.com/prussianmaster1871">
    <img src="https://github.com/prussianmaster1871.png?size=100" alt="Pedro" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Pedro Senes Velloso Ribeiro</b>
  </a><br>pedro.senes@pucpr.edu.br
</p>

<p align="center">
  <a href="https://github.com/Vareja0">
    <img src="https://github.com/Vareja0.png?size=100" alt="Riscala" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Riscala Miguel Fadel Neto</b>
  </a><br>riscala.neto@pucpr.edu.br
</p>

<p align="center">
  <a href="https://github.com/VictorFadel06">
    <img src="https://github.com/VictorFadel06.png?size=100" alt="Victor" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Victor Valerio Fadel</b>
  </a><br>victor.fadel@pucpr.edu.br
</p>

<p align="center">
  <a href="https://github.com/DraNefario">
    <img src="https://github.com/DraNefario.png?size=100" alt="Thomas" style="border-radius: 50%; width:100px; height:auto;"><br>
    <b>Thomas Manussadjian Steinhausser</b>
  </a><br>thomas.manussadjian@pucpr.edu.br
</p>

---

This project reflects proficiency in full-stack development, system design, and deploying robust solutions for real-world problems. For further details, please refer to the [repository](https://github.com/marshmll/purrfect-match).
