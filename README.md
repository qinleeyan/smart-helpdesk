# Smart IT Helpdesk 🚀

**Smart IT Helpdesk** is a professional-grade IT Service Management (ITSM) system designed to optimize internal support operations, reduce response times, and improve employee productivity through structured ticketing workflows and automated knowledge management.

---

## �️ System Overview

The Smart IT Helpdesk serves as the central nervous system for organizational IT support. By integrating a searchable Knowledge Base with a high-priority ticketing engine, the system ensures that critical technical issues are resolved within defined service levels while routing repetitive inquiries through self-service channels.

### Core Capabilities:
- **Incident & Request Management**: Full lifecycle tracking of IT issues with categorized routing and assignee ownership.
- **SLA-Driven Workflows**: Automatic enforcement of Response Time targets based on incident priority (Urgent, High, Normal, Low).
- **Proactive Knowledge Base**: A centralized repository of technical documentation that surfaces solutions during the ticket creation process to deflect redundant reports.
- **Real-time Analytics**: High-level data visualization for management to monitor support volume trends and system health.

---

## 📸 System Interface

### 1. Unified Dashboard
The command center for IT administrators, featuring real-time KPI tracking, distribution metrics, and an prioritized incident queue.
![Dashboard & Tickets](./gallery/dashboard_ticket.png)

### 2. Operational Analytics
Interactive data visualizations (powered by Recharts) providing insights into high-volume incident categories and daily trend analysis to identify recurring infrastructure bottlenecks.
![Analytics](./gallery/analytics.png)
![Statistics](./gallery/statistik.png)

### 3. Incident Lifecycle Tracking
Detailed audit logs and activity history for every support request, ensuring accountability and transparent communication between support staff and employees.
![Ticket Details](./gallery/ticket.png)

### 4. Smart Knowledge Repository
A public-facing portal for self-directed troubleshooting, fully integrated with the incident reporting engine for instant solution suggestions.
![KB Information](./gallery/information.png)

### 5. Enterprise Security & Access Control
Secure authentication layer with Role-Based Access Control (RBAC) to maintain internal data integrity and separate administrative tasks from general user reporting.
![Login Admin](./gallery/login_admin.png)

---

## ⚙️ Technical Architecture

Built for performance and scalability using a modern Single Page Application (SPA) architecture.

- **Frontend Engine**: React 19 optimized with Inertia.js for seamless, zero-page-reload navigation.
- **Styling & UI**: Tailwind CSS coupled with the shadcn/ui design system for a premium, accessible user interface.
- **Backend Infrastructure**: Laravel 11 providing a robust, secure PHP foundation for data persistence and business logic.
- **Database**: Supports SQLite for lightweight deployment and MySQL/PostgreSQL for enterprise scaling.
- **Design Tokens**: Fully implemented dark-mode with HSL-driven variables for consistent branding.

---

## � Deployment Guide

Follow these steps to deploy and initialize the system environment.

### Prerequisites
- PHP 8.2+
- Node.js 20+
- Composer & NPM

### Setup Instructions

```bash
# 1. Clone the environment
git clone https://github.com/qinleeyan/smart-helpdesk.git
cd smart-helpdesk

# 2. Install core dependencies
composer install
npm install

# 3. Configure environment parameters
cp .env.example .env
php artisan key:generate

# 4. Initialize Database & Seed Data
# Note: This command populates the system with realistic incident records.
php artisan migrate:fresh --seed

# 5. Launch Application Services
php artisan serve
npm run dev
```

### Access Credentials (Default)

| Role | Email | Password |
|------|-------|----------|
| Administrator | `admin@example.com` | `password` |
| Standard User | `test@example.com` | `password` |

---
*Documentation for Smart IT Helpdesk v1.0.0*

