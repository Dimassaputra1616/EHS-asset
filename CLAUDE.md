# HSE Asset Management - Project Context & Rules

This project is a modern Asset Management system built specifically for Health, Safety, and Environment (HSE) needs.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Database**: PostgreSQL
- **UI/CSS**: Bootstrap 5 + Vite
- **DataTables**: Yajra DataTables (Server-side)
- **Role-Based Access Control**: Spatie Laravel-Permission
- **Icons**: Bootstrap Icons
- **Auth Background**: Custom image support at `public/images/auth/welcome-bg.png` (Overlay enabled)
- **App Logo**: Custom image support at `public/images/logo/app-logo.png` (Fallback to SVG)

## 🎨 Design System
- **Theme Color**: HSE Red (`#C0392B`)
- **Backgrounds**: Slate Dark & Clean Light themes
- **Typography**: `Inter` (Google Fonts)
- **Component Style**: Modern, glassmorphism, rounded edges (`rounded-4`), soft shadows (`shadow-sm`, `shadow-lg`).

## 🤖 AI Agent Guidelines
**To prevent token exhaustion and repeating context, AI assistants MUST follow these rules:**
1. **Per-Component Architecture**: Maintain separation of concerns. Controllers, Models, and Views should be modular.
2. **Yajra DataTables**: Always use server-side Yajra DataTables for list views to ensure performance.
3. **Form Validations**: Always include Laravel Form Requests or controller-level validation with clear Bootstrap form feedback (`is-invalid`).
4. **Consistency**: Use `btn-hse-red` for primary actions. Use Bootstrap utility classes over custom CSS where possible.

## 📝 Changelog & Recent Updates
*(Update this section whenever major changes occur)*
- **2026-04-27**: Set up Laravel 11, configured PostgreSQL.
- **2026-04-27**: Implemented Spatie RBAC (Admin, Staff, Karyawan).
- **2026-04-27**: Built Config Master module.
- **2026-04-27**: Built Categories & Locations CRUD with DataTables.
- **2026-04-27**: Overhauled Auth (Login/Register/Forgot) UI to Bootstrap 5 + Premium Modern Design.
