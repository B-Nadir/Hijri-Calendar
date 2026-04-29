# Mumineen Hijri Calendar

A modern, responsive, and elegant Hijri-Gregorian Calendar system designed specifically for the Mumineen community. Built with **Laravel**, **Tailwind CSS**, and **Alpine.js**, this calendar combines functionality with premium aesthetics.

![Calendar Preview](https://github.com/B-Nadir/Hijri-Calendar/raw/main/preview.png)

## ✨ Features

- **Dual Calendar Support**: Seamlessly browse between Hijri and Gregorian dates.
- **Miqaat Management**: Specialized event tracking with visual dot indicators and detailed modal views.
- **Premium Typography**: Selective use of the **Al-Kanz** font for Arabic/Hijri elements, paired with clean sans-serif typography for English content.
- **Modern UI**: Dark mode-ready, vibrant color palettes, and glassmorphism-inspired design.
- **Fully Responsive**: Optimized for desktop and mobile viewing.
- **Quick Navigation**: "Today" button and sequential month navigation.

## 🚀 Tech Stack

- **Backend**: Laravel (Blade Templating)
- **Styling**: Tailwind CSS
- **Interactivity**: Alpine.js
- **Icons**: Lucide Icons
- **Fonts**: Al-Kanz (Custom), Helvetica/Arial (System)

## 🛠️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/B-Nadir/Hijri-Calendar.git
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Configure your `.env` file and generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run the development server:
   ```bash
   php artisan serve
   ```

## 📖 Usage

- **Navigation**: Use the large arrow buttons in the topbar to move between months.
- **Events**: Click on any date containing a dot to view the "Miqaat Details" modal.
- **Center Label**: The topbar displays the current Hijri month (in Blue) and Gregorian month (in Purple) for quick reference.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/B-Nadir/Hijri-Calendar/issues).

---
Created with ❤️ by [Burhanuddin Nadir](https://github.com/B-Nadir)
