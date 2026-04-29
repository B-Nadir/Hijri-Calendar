<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20Logo%20%26%20Wordmark/-/light.svg" width="400" alt="Laravel Logo"></a></p>

# Mumineen Hijri Calendar

## About Mumineen Hijri Calendar

The Mumineen Hijri Calendar is a robust, web-based calendar application built on the [Laravel](https://laravel.com) framework. It provides a seamless interface for managing both Hijri and Gregorian dates, specialized for the unique miqaat and event tracking needs of the community.

The system leverages:
- **Laravel 11.x** for back-end routing and logic.
- **Tailwind CSS** for a modern, responsive design.
- **Alpine.js** for lightweight, reactive front-end interactions.
- **Custom Fonts** (Al-Kanz) for premium Arabic typography.

## Features

- **Dual Navigation**: Effortlessly switch and sync between Hijri and Gregorian views.
- **Miqaat System**: Visual dot indicators for events with comprehensive detail modals.
- **Dynamic Header**: Real-time status display of current Hijri and Gregorian months.
- **Responsive UI**: Optimized for all devices, from mobile phones to high-resolution desktops.
- **Clean Architecture**: Built as a standalone Laravel project for easy deployment and integration.

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/B-Nadir/Hijri-Calendar.git
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Compile assets & serve:**
   ```bash
   npm run dev
   php artisan serve
   ```

## License

The Mumineen Hijri Calendar is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

Developed with ❤️ by [Burhanuddin Nadir](https://github.com/B-Nadir).
