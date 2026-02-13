<div align="center">

# 🤖 AI Support Chatbot Platform

### Enterprise-Ready Multi-Site AI Chat + Ticket System

[![Laravel](https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel)]()
[![MySQL](https://img.shields.io/badge/MySQL-Database-blue?style=for-the-badge&logo=mysql)]()
[![WebSockets](https://img.shields.io/badge/Realtime-WebSockets-green?style=for-the-badge)]()
[![AI](https://img.shields.io/badge/AI-Integrated-purple?style=for-the-badge)]()

---

</div>

---

# 📌 Project Overview

AI Support Chatbot Platform is a modern SaaS-ready system that provides:

- 🤖 AI-powered chat responses
- 💬 Real-time messaging
- 🎫 Smart Chat → Ticket conversion
- 👨‍💼 Admin dashboard for support agents
- 🌍 Multi-site integration via widget
- 🔐 Role-based access control

This project is designed to simulate a real-world production-ready customer support platform.

---

# 🧠 System Architecture


Visitor
↓
Widget (JavaScript)
↓
Laravel API
↓
AI Engine
↓
Conversation Storage (MySQL)
↓
Ticket System (if needed)
↓
Admin Panel (Support Agents)


---

# 🔥 Core Features

## 1️⃣ AI Chat Engine

- AI responses integrated
- Context-aware conversation
- Multi-message memory support
- Scalable model integration (OpenRouter compatible)

---

## 2️⃣ Smart Ticket System

Chat automatically detects issues:


"J’ai un problème de connexion"


Bot replies:


Voulez-vous créer un ticket ? (oui/non)


If confirmed:

✔ Ticket is created  
✔ Linked to conversation  
✔ Visible in Admin Panel  

---

## 3️⃣ Real-Time Messaging

- WebSocket powered
- Instant message delivery
- Support replies appear immediately
- No refresh required

---

## 4️⃣ Admin Panel

Admin can:

- View tickets
- Filter by status
- Reply to conversations
- Change ticket status
- Manage sites
- Monitor activity

---

## 5️⃣ Multi-Site Widget Integration

External websites integrate chat using:

```html
<script
  src="http://localhost/widget.js"
  data-site-key="SITE_KEY"
></script>

Each site has:

Unique site_key

Separate conversations

Separate tickets

🛠 Tech Stack
Layer	Technology
Backend	Laravel 11
Database	MySQL
Frontend	Blade + Tailwind
Realtime	WebSockets (Reverb)
AI	OpenRouter API
Auth	Laravel Auth
Queue	Database Queue
📂 Project Structure
app/
 ├── Http/
 │    ├── Controllers/
 │    ├── Middleware/
 │
 ├── Models/
 │
 ├── Events/

routes/
 ├── web.php
 ├── api.php

resources/views/
 ├── admin/
 ├── dashboard.blade.php

public/
 ├── widget.js
🔐 Security Features

CSRF protection

Admin middleware

Role-based authorization

Site key validation

Environment-based secret management

📊 Database Models
Users

name

email

is_admin

Sites

site_key

is_active

Conversations

site_id

visitor_id

ticket_offer_pending

ticket_draft_subject

ticket_draft_description

Messages

conversation_id

role (user | bot | support)

content

Tickets

site_id

conversation_id

visitor_id

subject

description

status

priority

🚀 Installation Guide
git clone https://github.com/KASPAROV2005/ai-support-chatbot.git
cd ai-support-chatbot

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate

php artisan serve
🔑 Environment Configuration

Add AI key in .env:

OPENROUTER_API_KEY=your_key_here
🧪 Development Roadmap

 AI Integration

 Ticket System

 Admin Panel

 Real-time Messaging

 Advanced AI memory

 SaaS Monetization

 Analytics Dashboard

 Docker Deployment

🎯 Academic Context

This project demonstrates:

Advanced Laravel architecture

Real-time systems

AI API integration

Event-driven design

SaaS-oriented thinking

Suitable for:

✔ Final Year Project (PFE)
✔ SaaS Prototype
✔ Production Simulation

👨‍💻 Author

Walid Islah
Computer Science Student
Software Engineering

<div align="center">
🚀 Built with passion & modern engineering principles
</div> ```

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
