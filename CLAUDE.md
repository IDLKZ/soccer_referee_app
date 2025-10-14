# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a comprehensive soccer referee management system built with Laravel 12.0, managing the complete workflow of referee operations including match assignments, logistics, financial processing, and administrative tasks. The system supports Russian, Kazakh, and English languages.

## Development Commands

### Initial Setup
```bash
composer run setup
```
Automated setup that installs dependencies, configures environment, runs migrations, seeds data, and builds assets.

### Development Workflow
```bash
composer run dev
```
Launches all development services concurrently: Laravel server, queue worker, log viewer, and Vite with hot reload.

### Individual Commands
- `composer run test` - Run PHPUnit test suite
- `npm run dev` - Frontend development server only
- `npm run build` - Production asset build
- `php artisan serve` - Laravel server only
- `php artisan migrate` - Database migrations
- `php artisan db:seed` - Seed database with sample data
- `php artisan pail` - Log viewer

## Architecture Overview

### Core Domain Model
The system manages five main areas: users with role-based access, match operations with referee assignments, geographic organization (countries/cities/clubs/stadiums), financial processing, and logistical support.

### Role System
Eight distinct roles with specific permissions:
- Administrator (App\Constants\Roles::ADMINISTRATOR)
- Refereeing Department Staff, Finance Department, Accountant
- Soccer Referees, Judge Inspectors, Observer

### Multi-language Support
All content entities use `_ru`, `_kk`, `_en` suffixes for Russian, Kazakh, and English fields respectively.

### Database Structure
- Users → Matches → Trips → Work Acts hierarchy
- Clubs with parent-child relationships
- Match flow tracking with operations and stages
- Geographic hierarchy: Countries → Cities → Clubs → Stadiums

## Key Patterns

### Constants Classes
Use App\Constants\ for enums (Roles, JudgeTypes, ClubTypes, etc.) rather than database enums.

### Model Generation
Models are generated via Reliese from database schema. Update database first, then regenerate models with `php artisan code:models`.

### Soft Deletes
Core entities use soft deletes. Check for `deleted_at` when querying.

### Financial Workflow
Match assignments → Trip creation → Work act generation → Payment processing. Each stage has specific responsible persons and deadlines.

## Development Setup Notes

- Default database: SQLite at `database/database.sqlite`
- Default user password: "admin123" (for seeded accounts)
- All core relationships use cascade deletes
- JSON fields used for flexible data (phone numbers, info fields)
- Queue worker required for background processing

## Testing

- PHPUnit configured with Feature and Unit suites
- Use `composer run test` for full test execution
- Tests cover role-based access, match operations, and financial workflows