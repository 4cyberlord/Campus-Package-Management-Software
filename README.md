# Student Package Management System

A robust Laravel-based package management system built with Filament, designed specifically for educational institutions to manage student package deliveries efficiently.

## Features

### Multi-Panel Authentication
- Admin Panel (`/admin`)
- Manager Panel (`/manager`)
- Student Panel (`/student`)
- Role-based access control using Filament Shield

### User Management
- Create and manage users with different roles
- Automatic password generation for new users
- Student ID and contact information management
- Role assignment and management

### Package Management
- Track packages through multiple status stages:
  - Pending
  - Received
  - Ready for Pickup
  - Picked Up
- Comprehensive package details:
  - Tracking number
  - Sender information
  - Courier details
  - Package type
  - Expected pickup date
  - Authorization settings

### Real-Time Notifications
- Instant status update notifications
- Badge counter for unread notifications
- Mark notifications as read functionality
- Real-time updates without page refresh

### Admin Features
- Complete user management
- Package status management
- Role and permission management
- System monitoring and control

### Manager Features
- Package status updates
- View all packages
- Track package history
- Student package management

### Student Features
- View personal packages
- Real-time notifications
- Package status tracking
- Notification management

### Security Features
- Role-based access control
- Secure authentication
- Password protection
- Data validation

## About The Project

This package management system is designed to streamline the process of handling student package deliveries in educational institutions. It provides a seamless experience for administrators, managers, and students to track and manage packages from arrival to pickup.

The system uses:
- Laravel 10
- Filament 3
- Filament Shield for permissions
- Real-time notifications
- Status management with enums
- Database notifications

## Key Benefits
- Efficient package tracking
- Real-time status updates
- Reduced administrative overhead
- Improved student experience
- Secure and reliable system
- User-friendly interface

## Technical Stack
- Laravel 10.x
- PHP 8.1+
- MySQL/SQLite
- Filament 3.x
- Livewire
- Tailwind CSS
