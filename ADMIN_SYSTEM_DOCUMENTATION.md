# Admin Management System - Web Gizi

## Overview
A complete admin authentication and authorization system has been implemented for the Web Gizi Laravel Livewire project. This system provides secure access to admin features with role-based access control.

## Features Implemented

### 1. Database Schema
- **Migration**: Added `role` and `is_active` columns to users table
- **Roles**: Support for 'admin' and 'user' roles
- **Status**: Active/inactive user status control

### 2. Middleware
- **AdminMiddleware**: Protects admin routes
  - Checks user authentication
  - Verifies admin role
  - Ensures account is active
  - Redirects unauthorized users to login

### 3. Authentication Controllers
- **AuthController**: Traditional Laravel controller for login/logout
  - Login form display
  - Authentication processing
  - Secure logout with session cleanup

### 4. Livewire Components
- **LoginPage**: Interactive Livewire login component
  - Real-time validation
  - Loading states
  - Error handling
  - Alternative to traditional forms

### 5. Views & UI
- **Auth Layout**: Modern, responsive authentication layout
  - Gradient background
  - Glass-morphism design
  - Bootstrap 5 & Font Awesome icons
  
- **Login Views**: 
  - Traditional Blade view (`admin/auth/login.blade.php`)
  - Livewire component view (`livewire/admin/auth/login-page.blade.php`)

- **Header Updates**: 
  - User dropdown with profile info
  - Logout functionality
  - Role display
  - Login button for guests

### 6. User Model Enhancements
- **Role Constants**: ROLE_ADMIN, ROLE_USER
- **Helper Methods**: 
  - `isAdmin()`: Check if user is admin
  - `isActive()`: Check if user is active
  - `canAccessAdmin()`: Combined check for admin access
- **Scopes**: `admins()`, `active()` for query filtering

### 7. Route Protection
- **Authentication Routes**: Login/logout endpoints
- **Protected Routes**: All admin_management routes require authentication and admin role
- **Middleware Groups**: Proper middleware application

### 8. Default Admin Accounts
Created via AdminUserSeeder:
- **Admin**: admin@webgizi.com / password123
- **Super Admin**: superadmin@webgizi.com / superpassword123
- **User**: user@webgizi.com / userpassword123 (for testing role restrictions)

## Access Points

### Admin Login URLs:
1. **Traditional Controller**: `/admin/login`
2. **Livewire Component**: `/admin/login-livewire`

### Protected Admin Area:
- **Dashboard**: `/admin_management/dashboard`
- **Data Desa**: `/admin_management/desa`
- **Data Posyandu**: `/admin_management/posyandu`
- **Status Gizi**: `/admin_management/status_gizi`
- **Type**: `/admin_management/type`

## Security Features

### Authentication Security:
- Password hashing using Laravel's default bcrypt
- Session regeneration after login
- CSRF protection on all forms
- Remember me functionality

### Authorization Security:
- Role-based access control
- Active status checking
- Automatic logout for deactivated accounts
- Middleware protection on all admin routes

### Session Security:
- Secure logout with session invalidation
- Token regeneration
- Session cleanup

## Usage Instructions

### For Developers:
1. **Run Migrations**: `php artisan migrate`
2. **Seed Admin Users**: `php artisan db:seed --class=AdminUserSeeder`
3. **Start Server**: `php artisan serve`
4. **Access Admin**: Visit `/admin/login`

### For Admins:
1. **Login**: Use provided admin credentials
2. **Access Dashboard**: Navigate through admin area
3. **Logout**: Use dropdown menu in header

### For Testing:
- Use different accounts to test role restrictions
- Try accessing admin routes without authentication
- Test account deactivation scenarios

## File Structure

```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   └── AuthController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Kernel.php (updated)
├── Livewire/Admin/Auth/
│   └── LoginPage.php
├── Models/
│   └── User.php (enhanced)
└── Providers/
    └── RouteServiceProvider.php (updated)

database/
├── migrations/
│   └── *_add_role_to_users_table.php
└── seeders/
    └── AdminUserSeeder.php

resources/views/
├── admin/auth/
│   └── login.blade.php
├── layouts/
│   ├── auth.blade.php
│   └── header.blade.php (updated)
└── livewire/admin/auth/
    └── login-page.blade.php

routes/
└── web.php (updated with auth routes)
```

## Configuration Notes

### Environment Setup:
- Ensure database connection is configured
- Session driver should be set up properly
- CSRF protection is enabled

### Middleware Registration:
- AdminMiddleware is registered as 'admin' in Kernel.php
- Applied to all admin_management routes

### Default Redirects:
- Successful login redirects to dashboard
- Unauthorized access redirects to login
- Logout redirects to login page

## Customization Options

### Styling:
- Auth layout uses modern gradient design
- Easily customizable via CSS variables
- Bootstrap 5 compatible

### Additional Features:
- Email verification can be added
- Password reset functionality
- Two-factor authentication support
- Permission-based access control

### Role Management:
- Easy to extend with additional roles
- Database-driven role system
- Flexible permission structure

## Testing Credentials

**Admin Access:**
- Email: admin@webgizi.com
- Password: password123

**Super Admin Access:**
- Email: superadmin@webgizi.com  
- Password: superpassword123

**Regular User (No Admin Access):**
- Email: user@webgizi.com
- Password: userpassword123

## Troubleshooting

### Common Issues:
1. **Migration Errors**: Ensure database is connected and writable
2. **Login Failures**: Check user exists and has correct role
3. **Route Access**: Verify middleware is properly applied
4. **Session Issues**: Check session configuration in .env

### Debug Tips:
- Use `php artisan route:list` to verify routes
- Check logs in `storage/logs/laravel.log`
- Verify database structure with `php artisan migrate:status`
- Test authentication with `Auth::check()` in tinker