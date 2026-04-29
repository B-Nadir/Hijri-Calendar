<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mumineen Calendar</title>
    <!-- Fonts -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='4' width='18' height='18' rx='2' ry='2'></rect><line x1='16' y1='2' x2='16' y2='6'></line><line x1='8' y1='2' x2='8' y2='6'></line><line x1='3' y1='10' x2='21' y2='10'></line></svg>">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        'primary-light': '#dbeafe',
                        secondary: '#7c3aed',
                        'secondary-light': '#ede9fe',
                        dark: '#0f172a',
                        muted: '#64748b',
                    },
                    borderRadius: {
                        'm3': '2rem',
                    },
                    boxShadow: {
                        'm3': '0 25px 50px -12px rgba(0, 0, 0, 0.12)',
                    }
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: 'Al-Kanz';
            src: url('/fonts/AL-KANZ.TTF') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body { font-family: 'Helvetica', 'Arial', sans-serif; }
        .font-kanz { font-family: 'Al-Kanz', serif; }
        [x-cloak] { display: none !important; }
        .card { border-radius: 1.5rem; transition: all 0.3s ease; }
        .rounded-m3 { border-radius: 2rem; }
        
        /* Ensure calendar is fully shown and not clipped */
        .calendar-container {
            min-height: 700px;
        }
        @media (max-width: 768px) {
            .calendar-container {
                min-height: 600px;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#F1F5F9] flex flex-col" x-data="calendarApp()" x-init="init()">

    <main class="flex-1 w-full max-w-[1600px] mx-auto p-4 md:p-6 flex items-center justify-center">
        <div class="w-full max-w-6xl flex flex-col gap-4">
            
            <!-- Removed Top Sidebar (Today, Current Month, Events) as requested -->

            <!-- Header -->
            <div class="flex items-center justify-between bg-white/70 backdrop-blur-xl p-6 rounded-m3 shadow-m3 border border-white/50">
                <div class="flex items-center gap-8">
                    <!-- Left: Previous Month -->
                    <button 
                        @click="changeMonth(-1)"
                        class="p-4 hover:bg-white hover:shadow-md rounded-2xl text-primary transition-all border border-transparent hover:border-gray-100 shadow-sm bg-gray-50/50"
                    >
                        <i data-lucide="chevron-left" class="w-8 h-8"></i>
                    </button>

                    <!-- Date Label -->
                    <div class="flex items-center">
                        <div class="flex items-center gap-3">
                            <h2 class="text-3xl font-black text-dark flex items-center gap-2">
                                <span x-text="MONTH_NAMES[currentHijri.month]"></span>
                                <span class="text-primary" x-text="currentHijri.year + ' H'"></span>
                            </h2>
                            <span class="text-3xl font-light text-gray-300">/</span>
                            <h2 class="text-2xl font-bold text-muted flex items-center gap-2">
                                <span x-text="GREGORIAN_MONTHS[currentGregMonth]"></span>
                                <span class="text-secondary" x-text="currentGregYear"></span>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <button 
                            @click="isAddModalOpen = true"
                            class="flex items-center gap-2 px-6 py-3 text-sm font-black text-white bg-dark hover:bg-black rounded-2xl transition-all shadow-lg shadow-dark/20"
                        >
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Add Miqaat
                        </button>

                        <button 
                            @click="goToToday()"
                            class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-primary bg-primary-light hover:bg-primary/20 rounded-2xl transition-all"
                        >
                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                            Today
                        </button>
                    </div>

                    <!-- Right: Next Month -->
                    <button 
                        @click="changeMonth(1)"
                        class="p-4 hover:bg-white hover:shadow-md rounded-2xl text-primary transition-all border border-transparent hover:border-gray-100 shadow-sm bg-gray-50/50"
                    >
                        <i data-lucide="chevron-right" class="w-8 h-8"></i>
                    </button>
                </div>
            </div>

            <!-- Grid -->
            <div class="flex-1 flex flex-col bg-white rounded-m3 shadow-xl overflow-visible relative border border-gray-100 mb-4 calendar-container">
                <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50/80">
                    <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                        <div class="py-5 text-center text-sm sm:text-base font-black text-muted/60 uppercase tracking-[0.2em]" x-text="day"></div>
                    </template>
                </div>

                <div class="flex-1 grid grid-cols-7 grid-rows-6">
                    <template x-for="(day, idx) in calendarData" :key="idx">
                        <div 
                            @click="handleDayClick(day)"
                            :class="{
                                'border-r border-b border-gray-50 flex flex-col gap-1 transition-all duration-300 hover:bg-gray-50/50 cursor-pointer overflow-visible p-2 sm:p-3': !day.isFiller,
                                'border-r border-b border-gray-50 bg-gray-50/30': day.isFiller,
                                'border-r-0': idx % 7 === 6,
                                'bg-primary-light/20': day.isToday
                            }"
                        >
                            <template x-if="!day.isFiller">
                                <div>
                                    <div class="flex justify-between items-start mb-1 sm:mb-2">
                                        <span 
                                            :class="day.isToday ? 'w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center bg-primary text-white rounded-[14px] ring-4 ring-primary/10 shadow-lg shadow-primary/20 font-black pt-1' : 'text-dark font-normal'"
                                            class="text-3xl sm:text-5xl transition-all duration-300 font-kanz"
                                            x-text="day.hijri.day"
                                        ></span>
                                        <span class="text-sm sm:text-lg font-bold text-muted/60 bg-gray-100/80 px-2 py-1 rounded-lg" x-text="day.gregorian.getDate()"></span>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-1 mt-auto">
                                        <template x-for="(miqaat, mIdx) in day.miqaats" :key="mIdx">
                                            <div 
                                                :class="miqaat.type === 'gregorian' ? 'bg-secondary' : 'bg-primary'"
                                                class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full shadow-sm"
                                                :title="miqaat.title"
                                            ></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <!-- Details Modal -->
    <div x-show="selectedDay" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-dark/60 backdrop-blur-sm" x-transition>
        <div @click.away="selectedDay = null" class="bg-white w-full max-w-md rounded-[32px] shadow-2xl overflow-hidden border border-white/20">
            <div class="relative h-32 bg-primary p-8 flex items-end justify-between overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                <div class="z-10" x-if="selectedDay">
                    <h3 class="text-white/80 font-bold text-lg uppercase tracking-widest" x-text="MONTH_NAMES[selectedDay.hijri.month]"></h3>
                    <p class="text-white text-5xl font-black leading-none mt-2" x-text="selectedDay.hijri.day + ', ' + selectedDay.hijri.year + ' H'"></p>
                </div>
                <button @click="selectedDay = null" class="absolute top-6 right-6 p-2 bg-white/20 hover:bg-white/30 text-white rounded-full transition-all backdrop-blur-md">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 max-h-[60vh] overflow-y-auto" x-if="selectedDay">
                <div class="flex items-center gap-4 mb-8 bg-gray-50 p-4 rounded-2xl">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-muted text-xs font-bold uppercase tracking-wider">Gregorian Date</p>
                        <p class="text-dark font-bold" x-text="formatGregorian(selectedDay.gregorian)"></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-black text-muted uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Events & Miqaats
                    </h4>
                    
                    <div class="space-y-3" x-show="selectedDay.miqaats.length > 0">
                        <template x-for="(miqaat, idx) in selectedDay.miqaats" :key="idx">
                            <div :class="miqaat.type === 'gregorian' ? 'bg-secondary-light/30 border border-secondary/10' : 'bg-gray-50 border border-gray-100'" class="p-5 rounded-2xl flex items-start gap-4 transition-all hover:translate-x-1">
                                <div :class="miqaat.type === 'gregorian' ? 'bg-secondary text-white' : 'bg-primary text-white'" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                    <i data-lucide="info" class="w-4.5 h-4.5"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-dark leading-tight text-2xl" x-text="miqaat.title"></h4>
                                    <template x-if="miqaat.description">
                                        <p class="text-sm text-muted mt-1 leading-relaxed" x-text="miqaat.description"></p>
                                    </template>
                                    <div class="flex items-center gap-2 mt-3">
                                        <span :class="miqaat.type === 'gregorian' ? 'bg-secondary text-white' : 'bg-white text-primary border border-primary/10'" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm" x-text="miqaat.type || 'Event'"></span>
                                        <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black text-muted uppercase tracking-wider border border-gray-100 shadow-sm flex items-center gap-1">
                                            <i data-lucide="clock" class="w-2.5 h-2.5"></i> <span x-text="miqaat.phase"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="py-12 flex flex-col items-center justify-center text-center opacity-40" x-show="selectedDay.miqaats.length === 0">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="info" class="w-8 h-8"></i>
                        </div>
                        <p class="font-bold">No Miqaats for this day</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-center">
                <button @click="selectedDay = null" class="px-8 py-3 bg-dark text-white rounded-2xl font-bold hover:bg-black transition-all shadow-lg shadow-dark/20">Close Details</button>
            </div>
        </div>
    </div>

    <!-- Add Miqaat Modal -->
    <div x-show="isAddModalOpen" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-dark/60 backdrop-blur-sm" x-transition>
        <div @click.away="isAddModalOpen = false" class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-dark">Add New Miqaat</h3>
                    <p class="text-muted text-sm font-medium">Create a new event for the calendar</p>
                </div>
                <button @click="isAddModalOpen = false" class="p-2 hover:bg-gray-100 rounded-full text-muted transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form @submit.prevent="alert('Miqaat added successfully (Simulation)!'); isAddModalOpen = false;" class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-muted uppercase tracking-widest">Event Title</label>
                    <input required type="text" placeholder="Enter miqaat title..." class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 focus:border-primary focus:bg-white outline-none transition-all font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div @click="dateType = 'hijri'" :class="dateType === 'hijri' ? 'border-primary bg-primary-light/20 text-primary' : 'border-gray-100 bg-gray-50 text-muted grayscale opacity-60'" class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col items-center gap-2">
                        <i data-lucide="moon" class="w-6 h-6"></i>
                        <span class="font-black text-xs uppercase tracking-widest">Hijri Date</span>
                    </div>
                    <div @click="dateType = 'gregorian'" :class="dateType === 'gregorian' ? 'border-primary bg-primary-light/20 text-primary' : 'border-gray-100 bg-gray-50 text-muted grayscale opacity-60'" class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col items-center gap-2">
                        <i data-lucide="globe" class="w-6 h-6"></i>
                        <span class="font-black text-xs uppercase tracking-widest">Gregorian</span>
                    </div>
                </div>

                <template x-if="dateType === 'hijri'">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2 col-span-1">
                            <label class="text-xs font-black text-muted uppercase tracking-widest">Day</label>
                            <input type="number" min="1" max="30" class="w-full px-4 py-4 bg-gray-50 rounded-2xl border border-gray-100 outline-none font-bold" value="1">
                        </div>
                        <div class="space-y-2 col-span-1">
                            <label class="text-xs font-black text-muted uppercase tracking-widest">Month</label>
                            <select class="w-full px-4 py-4 bg-gray-50 rounded-2xl border border-gray-100 outline-none font-bold truncate">
                                <template x-for="(name, idx) in MONTH_NAMES" :key="idx">
                                    <option :value="idx" x-text="name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-2 col-span-1">
                            <label class="text-xs font-black text-muted uppercase tracking-widest">Year</label>
                            <input type="number" class="w-full px-4 py-4 bg-gray-50 rounded-2xl border border-gray-100 outline-none font-bold" x-model="currentHijri.year">
                        </div>
                    </div>
                </template>
                <template x-if="dateType === 'gregorian'">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted uppercase tracking-widest">Date</label>
                        <input type="date" class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 outline-none font-bold">
                    </div>
                </template>

                <div class="space-y-2">
                    <label class="text-xs font-black text-muted uppercase tracking-widest">Description (Optional)</label>
                    <textarea rows="3" placeholder="Enter details..." class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 focus:border-primary focus:bg-white outline-none transition-all font-medium"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="button" @click="isAddModalOpen = false" class="flex-1 py-4 bg-gray-100 text-muted font-black rounded-2xl hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-primary text-white font-black rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">Save Miqaat</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Hijri Logic constants
        const KABISA_YEAR_REMAINDERS = [2, 5, 8, 10, 13, 16, 19, 21, 24, 27, 29];
        const DAYS_IN_YEAR = [30, 59, 89, 118, 148, 177, 207, 236, 266, 295, 325];
        const DAYS_IN_30_YEARS = [
            354, 708, 1063, 1417, 1771, 2126, 2480, 2834, 3189, 3543,
            3898, 4252, 4606, 4961, 5315, 5669, 6024, 6378, 6732, 7087,
            7441, 7796, 8150, 8504, 8859, 9213, 9567, 9922, 10276, 10631,
        ];

        function isJulian(date) {
            const year = date.getFullYear();
            if (year < 1582) return true;
            if (year === 1582) {
                if (date.getMonth() < 9) return true;
                if (date.getMonth() === 9 && date.getDate() < 5) return true;
            }
            return false;
        }

        function gregorianToAJD(date) {
            let year = date.getFullYear();
            let month = date.getMonth() + 1;
            const day = date.getDate() + (date.getHours() / 24) + (date.getMinutes() / 1440);

            if (month < 3) {
                year--;
                month += 12;
            }

            let b = 0;
            if (!isJulian(date)) {
                const a = Math.floor(year / 100);
                b = 2 - a + Math.floor(a / 4);
            }

            return Math.floor(365.25 * (year + 4716)) + Math.floor(30.6001 * (month + 1)) + day + b - 1524.5;
        }

        function ajdToGregorian(ajd) {
            const z = Math.floor(ajd + 0.5);
            const f = ajd + 0.5 - z;
            let a = z;
            if (z >= 2299161) {
                const alpha = Math.floor((z - 1867216.25) / 36524.25);
                a = z + 1 + alpha - Math.floor(0.25 * alpha);
            }
            const b = a + 1524;
            const c = Math.floor((b - 122.1) / 365.25);
            const d = Math.floor(365.25 * c);
            const e = Math.floor((b - d) / 30.6001);

            const dayWithFraction = b - d - Math.floor(30.6001 * e) + f;
            const month = e < 14 ? e - 2 : e - 14;
            const year = month < 2 ? c - 4715 : c - 4716;

            return new Date(year, month, Math.floor(dayWithFraction));
        }

        class HijriDate {
            constructor(year, month, day) {
                this.year = year;
                this.month = month;
                this.day = day;
            }

            static isKabisa(year) {
                return KABISA_YEAR_REMAINDERS.includes(year % 30);
            }

            static daysInMonth(year, month) {
                return (month === 11 && this.isKabisa(year)) || month % 2 === 0 ? 30 : 29;
            }

            dayOfYear() {
                return this.month === 0 ? this.day : DAYS_IN_YEAR[this.month - 1] + this.day;
            }

            toAJD() {
                const y30 = Math.floor(this.year / 30.0);
                let ajd = 1948083.5 + y30 * 10631 + this.dayOfYear();
                if (this.year % 30 !== 0) {
                    ajd += DAYS_IN_30_YEARS[(this.year % 30) - 1];
                }
                return ajd;
            }

            static fromAJD(ajd) {
                let left = Math.floor(ajd - 1948083.5);
                const y30 = Math.floor(left / 10631.0);
                left -= y30 * 10631;

                let i = 0;
                while (i < 30 && left > DAYS_IN_30_YEARS[i]) {
                    i++;
                }
                const year = Math.round(y30 * 30 + i);
                if (i > 0) {
                    left -= DAYS_IN_30_YEARS[i - 1];
                }

                i = 0;
                while (i < 12 && left > (DAYS_IN_YEAR[i] || 355)) {
                    i++;
                }
                const month = i;
                const date = i > 0 ? Math.round(left - DAYS_IN_YEAR[i - 1]) : Math.round(left);

                return new HijriDate(year, month, date);
            }

            static fromGregorian(date) {
                return this.fromAJD(gregorianToAJD(date));
            }

            toGregorian() {
                return ajdToGregorian(this.toAJD());
            }
        }

        function calendarApp() {
            return {
                currentDate: new Date(),
                currentGregMonth: new Date().getMonth(),
                currentGregYear: new Date().getFullYear(),
                currentHijri: { year: 1447, month: 0, day: 1 },
                selectedDay: null,
                isAddModalOpen: false,
                miqaatsMap: @json($miqaats),
                MONTH_NAMES: @json($monthNames),
                GREGORIAN_MONTHS: Array.from({length: 12}, (_, i) => new Date(0, i).toLocaleString('default', { month: 'long' })),
                calendarData: [],

                init() {
                    const today = new Date();
                    const hToday = HijriDate.fromGregorian(today);
                    this.currentHijri = { year: hToday.year, month: hToday.month, day: hToday.day };
                    this.currentGregMonth = today.getMonth();
                    this.currentGregYear = today.getFullYear();
                    
                    this.refreshCalendar();
                    this.$nextTick(() => lucide.createIcons());
                },

                refreshCalendar() {
                    const year = parseInt(this.currentHijri.year);
                    const month = parseInt(this.currentHijri.month);
                    
                    const firstDayHijri = new HijriDate(year, month, 1);
                    const startDayOfWeek = firstDayHijri.toGregorian().getDay();
                    const daysInMonth = HijriDate.daysInMonth(year, month);
                    
                    const days = [];
                    const today = new Date();
                    const todayHijri = HijriDate.fromGregorian(today);

                    // Previous month filler slots
                    for (let i = 0; i < startDayOfWeek; i++) {
                        days.push({ isFiller: true, miqaats: [] });
                    }
                    
                    // Current month days
                    for (let i = 1; i <= daysInMonth; i++) {
                        const hDate = new HijriDate(year, month, i);
                        const gDate = hDate.toGregorian();
                        const isToday = hDate.day === todayHijri.day && 
                                        hDate.month === todayHijri.month && 
                                        hDate.year === todayHijri.year;
                                        
                        days.push({
                            hijri: hDate,
                            gregorian: gDate,
                            isFiller: false,
                            isToday: isToday,
                            miqaats: this.miqaatsMap[`${month}-${i}`] || []
                        });
                    }
                    
                    // Next month filler slots
                    const totalSlots = 42; 
                    const remainingSlots = totalSlots - days.length;
                    for (let i = 0; i < remainingSlots; i++) {
                        days.push({ isFiller: true, miqaats: [] });
                    }
                    
                    this.calendarData = days;

                    // Sync Gregorian dropdowns to the first day of this Hijri month
                    const firstDayGreg = firstDayHijri.toGregorian();
                    this.currentGregMonth = firstDayGreg.getMonth();
                    this.currentGregYear = firstDayGreg.getFullYear();

                    this.$nextTick(() => lucide.createIcons());
                },

                changeMonth(offset) {
                    let month = parseInt(this.currentHijri.month) + offset;
                    let year = parseInt(this.currentHijri.year);

                    if (month > 11) {
                        month = 0;
                        year++;
                    } else if (month < 0) {
                        month = 11;
                        year--;
                    }
                    
                    this.currentHijri.month = month;
                    this.currentHijri.year = year;
                    this.refreshCalendar();
                },

                goToToday() {
                    const today = new Date();
                    const hToday = HijriDate.fromGregorian(today);
                    this.currentHijri = { year: hToday.year, month: hToday.month, day: hToday.day };
                    this.refreshCalendar();
                },

                handleDayClick(day) {
                    if (!day.isFiller) {
                        this.selectedDay = day;
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                formatGregorian(date) {
                    return date.toLocaleDateString('default', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                }
            }
        }
    </script>
</body>
</html>
